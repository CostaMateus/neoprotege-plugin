<?php

    // NOT ALLOW DIRECT ACCESS
    if ( $_SERVER["REQUEST_METHOD"] == "GET" && realpath(__FILE__) == realpath( $_SERVER["SCRIPT_FILENAME"] ) )
    {
        header( "HTTP/1.0 403 Forbidden", TRUE, 403 );
        die( header( "location: http://127.0.0.1:8000/" ) );
    }

    const KEY = "e1ec33182b8af04";
    const URL = "https://api.hinova.com.br/api/sga/api/v1/ws_SGA.php";

	function encode( $cpf )
	{
		$token   = NULL;
		$cpf     = base64_encode( $cpf );
		$key     = base64_encode( md5( KEY ) );

        $cpf_len = strlen( $cpf );
        $key_len = strlen( $key );

		for ( $i = 0; $i < ( 50 - $cpf_len ); $i++ ) $cpf   .= $i == 0 ? " " : rand( 0, 9 );

		for ( $i = 0; $i < ( 50 - $key_len ); $i++ ) $key   .= $i == 0 ? " " : rand( 0, 9 );

		for ( $i = 0; $i < ( 50 );            $i++ ) $token .= substr( $cpf, $i, 1 ) . substr( $key, $i, 1 );

		return base64_encode($token);
	}

	function generate_billet( $data )
	{
		$curl   = curl_init();
		$fields = http_build_query( $data );

		curl_setopt_array( $curl, [
			CURLOPT_URL            => URL,
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
            $aux[ "date"  ]   = formatDate( $result[ "msg" ][ 0 ][ "data_vencimento" ] );
            $aux[ "value" ]   = number_format( $result[ "msg" ][ 0 ][ "valor" ], 2, ",", "." );

            $result[ "data" ] = $aux;

            unset( $result[ "msg" ] );
        }

        return $result;
	}

	function formatDate( $date )
	{
        $date = explode( "-", $date );
        $date = array_reverse( $date );
        $date = implode( "/", $date );

		return $date;
	}

	$array   = [
		"cod"   => "01001",
		"emp"   => "522",
		"cpf"   => encode( $_POST["dfsCpf"] ),
		"tipo"  => "2",
		"total"	=> "10"
	];

	$retorno = generate_billet( $array );

	echo json_encode( $retorno );

	exit();
