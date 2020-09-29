<?php

	$cliente = $_POST['cliente'];

	require_once("../../../conexao.php");

	// Create connection
	$conn = mysqli_connect($servidor, $usuario, $senha, $banco);

	// Check connection
	if (!$conn) 
	{
			  die("Falha na Conexão: " . mysqli_connect_error());
	}
	else
	{	//VERIFICA SE EXISTEM PAGAMENTOS PARA O CLIENTE 
		$sql_consulta = "SELECT count(*) as quantos FROM pagamento where cpfCliente = '".$_REQUEST['cpf']."' ";
		
		
			
		$result = mysqli_query($conn, $sql_consulta);
			
		while($row = mysqli_fetch_assoc($result)) 
		{
		$quantos = $row["quantos"];
		}		


		$cpfs 		= array();
		$nomes 		= array();
		$rgs        = array();
    $enderecos  = array();

    $diasferias = 0;
    
    $sql_numero_dias_ferias = "SELECT duracaoFerias from ferias where cpfCLiente like '%".$_REQUEST['cpf']."%' ";

    $result_dias_ferias= mysqli_query($conn, $sql_numero_dias_ferias);
		
    while($rowfr = mysqli_fetch_assoc($result_dias_ferias)) 
    {//VERIFICA SE O CLIENTE QUANDO PLANO ANUAL TEM FÉRIAS MARCADAS PARA ACRESCENTAR A CARÊNCIA PARA O PRÓXIMO PAGAMENTO
    $diasferias	 = $diasferias + $rowfr["duracaoFerias"];		 
    }		

    if($diasferias != 0)
    {
      $diaspagamento = 365 + $diasferias;
    }
    else
    {
      $diaspagamento = 365 ;
    }


		
		$sql_lista = "SELECT 
                a.plano
		           ,a.dataPagamento
               ,a.situacaoPagamento
              , case when a.plano = 1 then (select  DATE_ADD(b.dataPagamento,interval 1 MONTH ) from pagamento b where a.dataPagamento = b.dataPagamento limit 1) 
                when a.plano = 2 then (select  DATE_ADD(b.dataPagamento,interval ".$diaspagamento." DAY ) from pagamento b where a.dataPagamento = b.dataPagamento limit 1) 
              end as proximoPagamento
		        FROM pagamento a
		        where a.cpfCliente like '%".$_REQUEST['cpf']."%' ";

					  
		  $result_lista = mysqli_query($conn, $sql_lista);
		
		  while($row = mysqli_fetch_assoc($result_lista)) 
		  {//MONTA A LISTA PARA EXIBIR NA TELA
			$planos[] 	 = $row["plano"];			
      $datapags[] 		 = $row["dataPagamento"];
      $sitspags[]   = $row["situacaoPagamento"];
      $proxpags[]   = $row["proximoPagamento"];
		  }		
				
					  
	}


?>



<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v4.0.1">
    <title>Academia de Ginastica · Cliente</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.5/examples/starter-template/">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
	<script src="../../../js/validacoes.js?1254"></script>
    <!-- Bootstrap core CSS -->
    <link href="../assets/dist/css/bootstrap.css" rel="stylesheet">

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>
    <!-- Custom styles for this template -->
    <link href="../starter-template.css" rel="stylesheet">
  </head>
  <body>



<main role="main" class="container">
<div class="jumbotron">
    <div class="col-sm-8 mx-auto">
  <p class="h1">Listar Pagamentos do CPF <?php echo $_REQUEST["cpf"];?></p>

  <table class="table">
    <thead class="thead-dark">
      <tr>
        <th scope="col">Selecionar</th>
        <th scope="col">Data</th>
        <th scope="col">Plano</th>
        <th scope="col">Pago</th>
        <th scope="col">Pr&oacute;x. Pagto.</th>
      </tr>
    </thead>
<?php 
if($quantos > 0)	
{
	for($i=0;$i<count($planos);$i++)
	{	
?>		
    <tbody>
      <tr>
        <th scope="row">
          <input type="radio" name="selecao" value="<?php echo $datapags[$i];?>" id="selecao" onClick="preencheBotoesConsultaPagto('<?php echo $_REQUEST["cpf"];?>', '<?php echo $datapags[$i];?>');" />
        </th>
        <td><?php $dataori = explode("-",$datapags[$i]); echo $dataori[2]."/".$dataori[1]."/".$dataori[0];?></td>
        <td><?php if($planos[$i] == '1') {echo "Mensal"; } else if($planos[$i] == '2') { echo "Anual";}?></td>
        <td><?php if($sitspags[$i] == '1') {echo "Sim"; } else if($sitspags[$i] == '2') { echo "Não";}?></td>        
        <td><?php $datacori = explode("-",$proxpags[$i]); echo $datacori[2]."/".$datacori[1]."/".$datacori[0];?></td>        
      </tr>
    </tbody>
<?php
	}
}
else
{
?>	
    <tbody>
      <tr>
        <td colspan=5>Nenhum registro encontrado</td>
      </tr>
    </tbody>
<?php
}
?>		
  </table>
  
  <a class="btn btn-primary btn-lg" href="pagamento.php?acao=cadastro&cpf=<?php echo $_REQUEST["cpf"];?>" role="button">Cadastrar</a>
<?php 
if($quantos > 0) 
{
?>
	<div id="botoesacao"></div>  
<?php
}
?>	  
  <a class="btn btn-secondary btn-lg" href="http://<?php echo $_SERVER["HTTP_HOST"];?>/back/1_Cliente/listarcliente.php" role="button">Cancelar</a>

    </div>
    </div>
    
</main><!-- /.container -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
      <script>window.jQuery || document.write('<script src="../assets/js/vendor/jquery.slim.min.js"><\/script>')</script><script src="../assets/dist/js/bootstrap.bundle.js"></script></body>
</html>
