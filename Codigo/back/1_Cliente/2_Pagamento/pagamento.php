<?php
require_once("../../../conexao.php");
if($_REQUEST['acao'] == "gravar")
{

	// Create connection
	$conn = mysqli_connect($servidor, $usuario, $senha, $banco);

	// Check connection
	if (!$conn) 
	{
			  die("Falha na Conexão: " . mysqli_connect_error());
	}
	else
	{


				//VERIFICA SE O CLIENTE JÁ POSSUI PLANO ANUAL CASO SELECIONOU PLANO MENSAL E VICE VERSA
				
				if($_POST['plano'] == "1")
				{
				
					$sql_consulta_planos = "SELECT count(*) as quantospl 
					FROM pagamento where cpfCliente = '".str_replace(".","",str_replace("-","",$_POST['cpf']))."' 
					and plano = 2 and situacaopagamento = 1 ";

					$result_cplanos = mysqli_query($conn, $sql_consulta_planos);
										
					while($rowcpl = mysqli_fetch_assoc($result_cplanos)) 
					{
					$quantospl = $rowcpl["quantospl"];
					}					


					if($quantospl > 0)
					{

						$conn->close();		
						echo '<script>window.alert("Cliente CPF '.$_POST['cpf'].' possui plano anual ativo ");</script>';
						echo '<script>window.location.href="listarpagamento.php?cpf='.$_POST["cpf"].'";</script>';
						exit;	

					}

				}


				if($_POST['plano'] == "2")
				{
				
					$sql_consulta_planos = "SELECT count(*) as quantospl 
					FROM pagamento where cpfCliente = '".str_replace(".","",str_replace("-","",$_POST['cpf']))."' 
					and plano = 1 and situacaopagamento = 1 ";

					$result_cplanos = mysqli_query($conn, $sql_consulta_planos);
										
					while($rowcpl = mysqli_fetch_assoc($result_cplanos)) 
					{
					$quantospl = $rowcpl["quantospl"];
					}					


					if($quantospl > 0)
					{

						$conn->close();		
						echo '<script>window.alert("Cliente CPF '.$_POST['cpf'].' possui plano mensal ativo ");</script>';
						echo '<script>window.location.href="listarpagamento.php?cpf='.$_POST["cpf"].'";</script>';
						exit;	

					}

				}				




				//VERIFICA SE JÁ EXISTE UM PAGAMENTO REALIZADO NOS ÚLTIMOS 30 DIAS CASO O PLANO FOR MENSAL 
				// E 1 ANO CASO O PLANO SEJA ANUAL
				$sql_consulta = "SELECT count(*) as quantos 
				FROM pagamento where cpfCliente = '".str_replace(".","",str_replace("-","",$_POST['cpf']))."' ";

				if($_POST['plano'] == "1")
				{
				$sql_consulta .= " and plano = 1 and DATEDIFF(dataPagamento,STR_TO_DATE('".$_POST["datapagamento"]."','%d/%m/%Y') ) between  - 30 and 30 ";		
				}			
				else
				if($_POST['plano'] == "2")
				{
				$sql_consulta .= " and plano = 2 and DATEDIFF(dataPagamento,STR_TO_DATE('".$_POST["datapagamento"]."','%d/%m/%Y') ) between  - 365 and 365 ";		
				}	

				$result = mysqli_query($conn, $sql_consulta);
					
				while($row = mysqli_fetch_assoc($result)) 
				{
				$quantos = $row["quantos"];
				}


				if($quantos > 0)
				{

					if($_POST['plano'] == "1")
					{
						$plano_escolhido = "Mensal";
					}
					else if($_POST['plano'] == "2")
					{
						$plano_escolhido = "Anual";
					}					

					$conn->close();		
					echo '<script>window.alert("Já existe um pagamento para o CPF '.$_POST['cpf'].' no intervalo desta data '.$_POST['datapagamento'].' para pagamento '.$plano_escolhido.' ");</script>';
					echo '<script>window.location.href="listarpagamento.php?cpf='.$_POST["cpf"].'";</script>';
					exit;	

				}



			
				try
				{
					$sql_insert = "insert into pagamento 
									(cpfCliente , plano, dataPagamento, situacaoPagamento) 
									values
									('".str_replace(".","",str_replace("-","",$_POST['cpf']))."','".$_POST['plano']."',STR_TO_DATE('".$_POST["datapagamento"]."', '%d/%m/%Y'), '".$_POST["situacaopagamento"]."' )";
			
					if(mysqli_query($conn, $sql_insert))
					{
						$conn->close();		
						echo '<script>window.alert("Pagamento para o CPF '.$_POST['cpf'].' cadastrado com sucesso");</script>';
						echo '<script>window.location.href="listarpagamento.php?cpf='.$_POST["cpf"].'";</script>';
						exit;	
					}
					else
                    {
                        throw new exception (mysqli_error($conn));
                    }
				
				}
				catch(Exception $e)
				{
					$conn->close();	
					echo '<script>window.alert("'.$e->getMessage().'");</script>';
					echo '<script>history.back();</script>';
					exit;			
				}					


	}
	
}
else if($_REQUEST['acao'] == "edicao")
{
	
	// Create connection
	$conn = mysqli_connect($servidor, $usuario, $senha, $banco);

	// Check connection
	if (!$conn) 
	{
			  die("Falha na Conexão: " . mysqli_connect_error());
	}
	else
	{	

		
		$sql_lista = "SELECT 
		 cpfCliente 
		,plano
		,dataPagamento
		,situacaoPagamento
		FROM pagamento 
		where cpfCliente = '".$_REQUEST['cpf']."' 
		and dataPagamento = '".$_REQUEST['data']."' 
		";
		
					  
		  $result_lista = mysqli_query($conn, $sql_lista);
		  
	
		//MONTA NA TELA O CLIENTE PARA EDIÇÃO
		  while($row = mysqli_fetch_assoc($result_lista)) 
		  {
			$cpf 	  		= $row["cpfCliente"];
			$plano 	  		= $row["plano"];	
			$datapag    	= $row["dataPagamento"];	
			$dataori 		= explode("-",$datapag); 
			$datapagamento  =  $dataori[2]."/".$dataori[1]."/".$dataori[0];	
			$situacaopagamento = $row["situacaoPagamento"];		
		
		  }		
				
					  
	}
	
}
else if($_REQUEST['acao'] == "atualizar")
{
	// Create connection
	$conn = mysqli_connect($servidor, $usuario, $senha, $banco);

	// Check connection
	if (!$conn) 
	{
			  die("Falha na Conexão: " . mysqli_connect_error());
	}
	else
	{	
/*
				//VERIFICA SE JÁ EXISTE UM PAGAMENTO REALIZADO NOS ÚLTIMOS 30 DIAS CASO O PLANO FOR MENSAL 
				// E 1 ANO CASO O PLANO SEJA ANUAL
				$sql_consulta = "SELECT count(*) as quantos 
				FROM pagamento where cpfCliente = '".str_replace(".","",str_replace("-","",$_POST['cpf']))."' ";

				if($_POST['plano'] == "1")
				{
				$sql_consulta .= " and plano = 1 and DATEDIFF(dataPagamento,STR_TO_DATE('".$_POST["datapagamento"]."','%d/%m/%Y') ) between  - 30 and 30 ";		
				}			
				else
				if($_POST['plano'] == "2")
				{
				$sql_consulta .= " and plano = 2 and DATEDIFF(dataPagamento,STR_TO_DATE('".$_POST["datapagamento"]."','%d/%m/%Y') ) between  - 365 and 365 ";		
				}	

				$result = mysqli_query($conn, $sql_consulta);
					
				while($row = mysqli_fetch_assoc($result)) 
				{
				$quantos = $row["quantos"];
				}


				if($quantos > 0)
				{

					if($_POST['plano'] == "1")
					{
						$plano_escolhido = "Mensal";
					}
					else if($_POST['plano'] == "2")
					{
						$plano_escolhido = "Anual";
					}					

					$conn->close();		
					echo '<script>window.alert("Já existe um pagamento para o CPF '.$_POST['cpf'].' no intervalo desta data '.$_POST['datapagamento'].' para pagamento '.$plano_escolhido.' ");</script>';
					echo '<script>window.location.href="listarpagamento.php?cpf='.$_POST["cpf"].'";</script>';
					exit;	

				}

*/


				try
				{
					$sql_update = "update pagamento 
									set 
									situacaoPagamento = '".$_POST["situacaopagamento"]."'
									where cpfCliente = '".str_replace(".","",str_replace("-","",$_POST['cpf']))."' 
									and dataPagamento =  STR_TO_DATE('".$_POST["datapagamento"]."', '%d/%m/%Y') 
									";

					if(mysqli_query($conn, $sql_update))
					{
						$conn->close();		
						echo '<script>window.alert("Pagamento para o CPF '.$_POST['cpf'].' atualizado com sucesso");</script>';
						echo '<script>window.location.href="listarpagamento.php?cpf='.$_POST["cpf"].'";</script>';
						exit;	
					}
					else
                    {
                        throw new exception (mysqli_error($conn));
                    }
				
				}
				catch(Exception $e)
				{
					$conn->close();	
					echo '<script>window.alert("'.$e->getMessage().'");</script>';
					echo '<script>history.back();</script>';
					exit;			
				}	
	}				
}	
else if($_REQUEST['acao'] == "exclusao")
{
	// Create connection
	$conn = mysqli_connect($servidor, $usuario, $senha, $banco);

	// Check connection
	if (!$conn) 
	{
			  die("Falha na Conexão: " . mysqli_connect_error());
	}
	else
	{	
			$dataori = explode("-",$_REQUEST["data"]); 
			$datapagamento  =  $dataori[2]."/".$dataori[1]."/".$dataori[0];		
				try
				{
					$sql_update = "delete from pagamento 
									where cpfCliente = '".str_replace(".","",str_replace("-","",$_REQUEST['cpf']))."' 
									and dataPagamento =  STR_TO_DATE('".$datapagamento."', '%d/%m/%Y') 
					";


					if(mysqli_query($conn, $sql_update))
					{
						$conn->close();		
						echo '<script>window.alert("Pagamento com CPF '.$_REQUEST['cpf'].' excluído com sucesso");</script>';
						echo '<script>window.location.href="listarpagamento.php?cpf='.$_REQUEST["cpf"].'";</script>';
						exit;	
					}
					else
                    {
                        throw new exception (mysqli_error($conn));
                    }
				
				}
				catch(Exception $e)
				{
					$conn->close();	
					echo '<script>window.alert("'.$e->getMessage().'");</script>';
					echo '<script>history.back();</script>';
					exit;			
				}	
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
    <title>Academia de Gin&aacute;stica · Cliente</title>

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
	  .error-message {
	  background-color: #fce4e4;
	  border: 1px solid #fcc2c3;
	  padding: 20px 30px;
	}
	
	.error-text {
	  color: #cc0033;
	  font-family: Helvetica, Arial, sans-serif;
	  font-size: 13px;
	  font-weight: bold;
	  line-height: 20px;
	  text-shadow: 1px 1px rgba(250,250,250,.3);
	}	  
    </style>
    <!-- Custom styles for this template -->
    <link href="../starter-template.css" rel="stylesheet">
  </head>
  <body>


<main role="main" class="container">
    
    <div class="jumbotron">
    <div class="col-sm-8 mx-auto">

<?php //VERIFICA SE A TELA FOI CHAMADA PARA CADASTRO OU EDIÇÃO. SE FOR PARA EDIÇÃO JÁ CARREGA OS CAMPOS E BLOQUEIA O CHAVE CPF ?>
  <p class="h1"><?php if($_REQUEST['acao'] == 'edicao') { ?> Editar <?php } else {   ?> Cadastrar <?php } ?>Pagamento</p>
<?php 
if($_REQUEST['acao'] == 'edicao') 
{ 
?> 
  <form name="cadastro" action="pagamento.php?acao=atualizar" method="post" enctype="multipart/form-data" onSubmit="return verificaCamposPagto();">
<?php  
}
else
{
?>
  <form name="cadastro" action="pagamento.php?acao=gravar" method="post" enctype="multipart/form-data" onSubmit="return verificaCamposPagto();">	
<?php 
}
?>
<div class="form-group">
    <label for="inputCPF">CPF</label>
    <input type="text" class="form-control" id="cpf" name="cpf" value="<?php echo $_REQUEST["cpf"];?>" placeholder="123.456.789.01" readonly>
  </div>
    
<div class="input-group mb-3">
  <div class="input-group-prepend">
      <label class="input-group-text" for="inputGroupSelect01">Plano</label>
  </div>
  <select class="custom-select" id="plano" name="plano" <?php if ($datapagamento != "") { ?>  value="<?php echo $datapagamento;?>" disabled <?php } ?> >
	<option value="">Selecione</option> 
    <option value="1" <?php if ($plano == "1") { ?>  selected <?php } ?> >Mensal</option>
    <option value="2" <?php if ($plano == "2") { ?>  selected <?php } ?> >Anual</option>
  </select>
</div>


<div class="input-group mb-3">
  <div class="input-group-prepend">
      <label class="input-group-text" for="inputGroupSelect01">Pago?</label>
  </div>
  <select class="custom-select" id="situacaopagamento" name="situacaopagamento">
	<option value="">Selecione</option> 
    <option value="1" <?php if ($situacaopagamento == "1") { ?>  selected <?php } ?> >Sim</option>
    <option value="2" <?php if ($situacaopagamento == "2") { ?>  selected <?php } ?> >Não</option>
  </select>
</div>
    
  <div class="form-group">
    <label for="inputDataPagamento">Data de Pagamento</label>
    <input type="text" class="form-control" id="datapagamento" name="datapagamento" <?php if ($datapagamento != "") { ?>  value="<?php echo $datapagamento;?>" readonly <?php } ?> maxlength="10"  onkeypress="mascaraData( this, event )" placeholder="01/01/1900">
  </div>
  
    
    <input class="btn btn-primary btn-lg" type="submit" value="Confirmar" >
  <a class="btn btn-secondary btn-lg" href="listarpagamento.php?cpf=<?php echo $_REQUEST["cpf"];?>" role="button">Cancelar</a>

</form>
	  <div class="error-message" id="errosform" style=" display:none;"></div>	

    </div>
    </div>
        
</main><!-- /.container -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
      <script>window.jQuery || document.write('<script src="../assets/js/vendor/jquery.slim.min.js"><\/script>')</script><script src="../assets/dist/js/bootstrap.bundle.js"></script></body>
</html>
