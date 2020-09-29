<?php


	require_once("../../conexao.php");

	// Create connection
	$conn = mysqli_connect($servidor, $usuario, $senha, $banco);

	// Check connection
	if (!$conn) 
	{
			  die("Falha na Conexão: " . mysqli_connect_error());
	}
	else
	{	//VERIFICA SE FOI DIGITADO UM PARÂMETRO DE PESQUISA. SE NÃO, LISTA TODOS OS CLIENTES
		$sql_consulta = "SELECT count(*) as quantos FROM cliente ";		
			
		$result = mysqli_query($conn, $sql_consulta);
			
		while($row = mysqli_fetch_assoc($result)) 
		{
		$quantos = $row["quantos"];
		}		


		$cpfs 		= array();
		$nomes 		= array();
		$rgs        = array();
		$enderecos  = array();
		
		$sql_lista = "SELECT 
		 cpfCliente 
		,nomeCliente
		FROM cliente order by 2 ";
					  
		  $result_lista = mysqli_query($conn, $sql_lista);
		
		  while($row = mysqli_fetch_assoc($result_lista)) 
		  {//MONTA A LISTA PARA EXIBIR NA TELA
			$cpfs[] 	 = $row["cpfCliente"];
			$nomes[] 	 = $row["nomeCliente"];			
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
    <title>Academia de Ginastica · Relatórios</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.5/examples/starter-template/">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

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
  <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">

  <div class="collapse navbar-collapse" id="navbarsExampleDefault">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="JavaScript:window.close();">Fechar<span class="sr-only" >(current)</span></a>
      </li>
    </ul>
  </div>
</nav>

<main role="main" class="container">
<div class="jumbotron">
    <div class="col-sm-8 mx-auto">
  <p class="h1">Listar Clientes Matriculados</p>

  <table class="table">
    <thead class="thead-dark">
      <tr>
        <th scope="col">Selecionar</th>
        <th scope="col">Matricula (CPF)</th>
        <th scope="col">Nome</th>
        <th scope="col">Data do Pagamento Realizado</th>        
        <th scope="col">Data Prevista do Próximo Pagamento</th>
      </tr>
    </thead>
<?php 
if($quantos > 0)	
{

	for($i=0;$i<count($cpfs);$i++)
	{	

    $diasferias = 0;
    
    $sql_numero_dias_ferias = "SELECT duracaoFerias from ferias where cpfCLiente like '%".$cpfs[$i]."%' ";

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


          $sql_lista_a = "SELECT 
          a.dataPagamento
          ,case when a.plano = 1 then (select  DATE_ADD(b.dataPagamento,interval 1 MONTH ) from pagamento b where a.dataPagamento = b.dataPagamento limit 1) 
                when a.plano = 2 then (select  DATE_ADD(b.dataPagamento,interval ".$diaspagamento." DAY ) from pagamento b where a.dataPagamento = b.dataPagamento limit 1) 
              end as proximoPagamento
          FROM pagamento a where a.cpfCliente = '".$cpfs[$i]."'  ";
                
          $result_lista_a= mysqli_query($conn, $sql_lista_a);
        
          while($rowl = mysqli_fetch_assoc($result_lista_a)) 
          {//MONTA A LISTA PARA EXIBIR NA TELA
          $cpf_pag 	 = $cpfs[$i];
          $nme_pag 	 = $nomes[$i];
          $dta_pag    = $rowl["dataPagamento"]	;
          $dta_prag    = $rowl["proximoPagamento"]	;          
          ?>    
          <tbody>
            <tr>
              <th scope="row">
                <input type="radio" name="radios" id="radio1" />
              </th>
              <td><?php echo $cpf_pag;?> </td>
              <td><?php echo $nme_pag;?></td>
              <td><?php $dataori = explode("-",$dta_pag); echo $dataori[2]."/".$dataori[1]."/".$dataori[0];?></td>
              <td><?php $dataorip = explode("-",$dta_prag); echo $dataorip[2]."/".$dataorip[1]."/".$dataorip[0];?></td>              
            </tr>
          </tbody>
          <?php 
          }	
        	
    }
}
else
{
?>	
    <tbody>
      <tr>
        <td colspan=5>Nenhum Cliente encontrado</td>
      </tr>
    </tbody>
<?php
}
?>	    
  </table>
  
  <a class="btn btn-secondary btn-lg" href="ListarRelatorios.html" role="button">Cancelar</a>
    </div>
    </div>
</main><!-- /.container -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
      <script>window.jQuery || document.write('<script src="../assets/js/vendor/jquery.slim.min.js"><\/script>')</script><script src="../assets/dist/js/bootstrap.bundle.js"></script></body>
</html>
