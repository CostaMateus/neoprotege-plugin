<?php

/**
 * @package NP_2_VIA
 * @since   1.0.0
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

class NP_2_VIA
{
    const KEY = "e1ec33182b8af04";
    const URL = "https://api.hinova.com.br/api/sga/api/v1/ws_SGA.php";

    private $data = [
        "cod"   => "01001",
        "emp"   => "522",
        "cpf"   => "",
        "tipo"  => "2",
        "total"	=> "10"
    ];

    public function __construct()
    {
        add_action( "wp_ajax_duplicate_ticket",        array( $this, "duplicate_ticket" ) );
        add_action( "wp_ajax_nopriv_duplicate_ticket", array( $this, "duplicate_ticket" ) );
    }

    public function duplicate_ticket()
    {
        $this->data[ "cpf" ] = $this->encode( $_POST[ "cpf" ] );

        echo json_encode( $this->generate_billet( $this->data ) );

        wp_die();
    }

	private function encode( $cpf )
	{
		$token   = NULL;
		$cpf     = base64_encode( $cpf );
		$key     = base64_encode( md5( self::KEY ) );

        $cpf_len = strlen( $cpf );
        $key_len = strlen( $key );

		for ( $i = 0; $i < ( 50 - $cpf_len ); $i++ ) $cpf   .= $i == 0 ? " " : rand( 0, 9 );

		for ( $i = 0; $i < ( 50 - $key_len ); $i++ ) $key   .= $i == 0 ? " " : rand( 0, 9 );

		for ( $i = 0; $i < ( 50 );            $i++ ) $token .= substr( $cpf, $i, 1 ) . substr( $key, $i, 1 );

		return base64_encode($token);
	}

	private function generate_billet( $data )
	{
		$curl   = curl_init();
		$fields = http_build_query( $data );

		curl_setopt_array( $curl, [
			CURLOPT_URL            => self::URL,
            CURLOPT_POST           => 1,
            CURLOPT_POSTFIELDS     => $fields,
			CURLOPT_RETURNTRANSFER => TRUE,
        ] );

        $result = curl_exec( $curl );

        curl_close( $curl );

        $result = json_decode( $result, TRUE );

        if ( $result[ "success" ] == "true" )
        {
            $aux[ "url"   ]   = $result[ "msg" ][ 0 ][ "url" ];
            $aux[ "code"  ]   = $result[ "msg" ][ 0 ][ "linha_digitavel" ];
            $aux[ "date"  ]   = $this->formatDate( $result[ "msg" ][ 0 ][ "data_vencimento" ] );
            $aux[ "value" ]   = number_format( $result[ "msg" ][ 0 ][ "valor" ], 2, ",", "." );

            $result[ "data" ] = $aux;

            unset( $result[ "msg" ] );
        }

        return $result;
	}

	private function formatDate( $date )
	{
        $date = explode( "-", $date  );
        $date = array_reverse( $date );
        $date = implode( "/", $date  );

		return $date;
	}
}

new NP_2_VIA();