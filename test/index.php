<!DOCTYPE html>
<html>
    <head>
        <title>Seu site</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://vsn4ik.github.io/bootstrap-submenu/dist/css/bootstrap-submenu.min.css">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/t/dt/dt-1.10.11/datatables.min.css" />
    </head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <form method="post" enctype="multipart/form-data" id="frmBoleto" name="frmBoleto">
                    <div class="form-group">
                        <label for="">Informe CPF OU CNPJ Para Solicitar Boleto</label>
                        <input type="tel" class="form-control" id="dfsCpf" placeholder="CPF/CNPJ" data-mask="99999999999?999" name="dfsCpf" value="" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <input type="button" class="btn btn-success btn-lg" id="pbEnviar" name="pbEnviar" value="Solicitar 2° Via" style="margin-top: 18px;">
                    </div>
                </form>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <div id="msg"></div>
                    <div>
                        <p id="msg"     ></p>
                        <p id="b_code"  ></p>
                        <p id="b_date"  ></p>
                        <p id="b_value" ></p>
                        <a id="b_url" target="_blank" ></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script>
    $( document ).ready( function( e ) {

        $( "#pbEnviar" ).click( function() {
            var dfsCpf = $( "#dfsCpf" ).val();

            $.ajax( {
                type: "POST",
                url: "exec.php",
                data: {
                    "dfsCpf" : dfsCpf
                },
                success: function( data ) {
                    $( "#msg" ).html( "" );

                    let result = JSON.parse( data );
                    console.log( result );

                    if ( result.success == "false" && result.codigo == "404" )
                    {
                        $( "#msg" ).html( result.msg );
                    }
                    else if ( result.success == "false" )
                    {
                        $( "#msg" ).html( "Houve um erro. Recarregue a página e tente novamente." );
                    }
                    else
                    {
                        $( "#b_code"  ).html( "Código: " + result.data.code     );
                        $( "#b_date"  ).html( "Vencimento: " + result.data.date );
                        $( "#b_value" ).html( "Valor: R$" + result.data.value   );
                        $( "#b_url"   ).html( "Clique aqui para baixar o boleto" ).attr( "href", result.data.url );
                    }
                }
            } );

        } );

    } );
</script>

<!-- 82000506020 -->