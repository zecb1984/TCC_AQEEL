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


				//VERIFICA SE EXISTE UM PLANO ANUAL PARA O CLIENTE SELECIONADO
				$sql_consulta = "SELECT min(DATE_FORMAT(dataPagamento,'%d/%m/%Y')) as minimo
				FROM pagamento where plano = 2 and situacaoPagamento = 1 and cpfCliente = '".str_replace(".","",str_replace("-","",$_POST['cpf']))."' ";

				$result = mysqli_query($conn, $sql_consulta);
					
				while($row = mysqli_fetch_assoc($result)) 
				{
					$minimo = $row["minimo"];
				}

				if(trim($minimo) == "")
				{
					$conn->close();		
					echo '<script>window.alert("O Cliente não tem direito a férias por ser um plano mensal ");</script>';
					echo '<script>window.location.href="listarferias.php?cpf='.$_POST["cpf"].'";</script>';
					exit;	
				}
				else
				{	
					
					//VERIFICA SE A DATA PROGRAMADA É MAIOR QUE O FINAL DA VIGÊNCIA DO PLANO ANUAL PARA O CLIENTE SELECIONADO

					// transforma a data do formato BR para o formato americano, ANO-MES-DIA
					$data1 = implode('-', array_reverse(explode('/', $_POST["dataferias"])));
					$data2 = implode('-', array_reverse(explode('/', $minimo)));


					// converte as datas para o formato timestamp
					$d1 = strtotime($data1); 
					$d2 = strtotime($data2);

					// verifica a diferença em segundos entre as duas datas e divide pelo número de segundos que um dia possui
					$dataFinal = ($d2 - $d1) /86400;


					
					if ($dataFinal > 0 )
					{
						$conn->close();		
						echo '<script>window.alert("Só é possível programar férias após o final da vigência do plano anual ( '.$minimo.' )  ");</script>';
						echo '<script>window.location.href="listarferias.php?cpf='.$_POST["cpf"].'";</script>';
						exit;	
					} 
					else
					{
							//VERIFICA SE O CLIENTE TEM MENOS DE 3 FÉRIAS PROGRAMADAS
							$sql_consulta_ferias = "SELECT count(*) as quantosferias 
							FROM ferias where cpfCliente = '".str_replace(".","",str_replace("-","",$_POST['cpf']))."' ";


							$result_ferias = mysqli_query($conn, $sql_consulta_ferias);
												
							while($rowf = mysqli_fetch_assoc($result_ferias)) 
							{
								$quantosferias = $rowf["quantosferias"];
							}

							if($quantosferias >= 3)
							{
								$conn->close();		
								echo '<script>window.alert("O Cliente já tem a quantidade máxima permitida de períodos de férias cadastrado. ");</script>';
								echo '<script>window.location.href="listarferias.php?cpf='.$_POST["cpf"].'";</script>';
								exit;	
							}
							else
							{
								//VERIFICA SE O CLIENTE TEM MAIS DE 30 DIAS DE FÉRIAS PROGRAMADOS
								$sql_consulta_dias_ferias = "SELECT duracaoFerias
								FROM ferias where cpfCliente = '".str_replace(".","",str_replace("-","",$_POST['cpf']))."' ";


								$result_dias_ferias = mysqli_query($conn, $sql_consulta_dias_ferias);
													
								while($rowdf = mysqli_fetch_assoc($result_dias_ferias)) 
								{
									$duracaoFerias = $duracaoFerias + $rowdf["duracaoFerias"];
								}	
								if($duracaoFerias >= 30)
								{
									$conn->close();		
									echo '<script>window.alert("O Cliente não tem saldo de férias disponível.");</script>';
									echo '<script>window.location.href="listarferias.php?cpf='.$_POST["cpf"].'";</script>';
									exit;	
								}
								else 
								{
									$saldo = $duracaoFerias + $_POST["duracao"];
									
									if($saldo > 30)
									{
										$deficit = 30 - $duracaoFerias;
										$conn->close();		
										echo '<script>window.alert("Cliente CPF '.$_POST['cpf'].' possui apenas '.$deficit.' dias de férias disponíveis no momento para programar ");</script>';
										echo '<script>window.location.href="listarferias.php?cpf='.$_POST["cpf"].'";</script>';
										exit;	
									}
									else
									{
									
											//VERIFICA SE O CLIENTE JÁ TEM FÉRIAS PROGRAMADAS NA DATA SELECIONADA SOMANDO OS DIAS
											$sql_consulta_ferias_d = "SELECT 
											DATE_FORMAT(dataFerias,'%d/%m/%Y') as dataFerias
											,duracaoFerias 
											FROM ferias where cpfCliente = '".str_replace(".","",str_replace("-","",$_POST['cpf']))."' ";
	
											$jatemferiasd = 0;
											$jatemferiasa = 0;											
											$result_ferias_d = mysqli_query($conn, $sql_consulta_ferias_d);
													
											while($rowfd = mysqli_fetch_assoc($result_ferias_d)) 
											{
												$dataferiasexiste    = $rowfd["dataFerias"];
												$duracaoferiasexiste = $rowfd["duracaoFerias"];
												
												$data4 = implode('-', array_reverse(explode('/', $dataferiasexiste)));

												// converte as datas para o formato timestamp
												$d3 = strtotime($data4); 
												$periodo_existente = strtotime($data4." + ".$duracaoferiasexiste." days" );

												if($d1 >= $d3  && $d1 <= $periodo_existente )
												{//interrompe o processo se o cliente já tem férias programadas entre o período da data escolhida
													$jatemferiasd = 1;
												}	

												if($d1 < $d3 )
												{//interrompe o processo se o cliente já tem férias programadas numa data posterior à data escolhida
													$jatemferiasa = 1;
												}	

											}	
											if($jatemferiasd == 1)
											{
												$conn->close();		
												echo '<script>window.alert("Cliente CPF '.$_POST['cpf'].' já possui férias programadas no intervalo com o dia '.$_POST["dataferias"].' com '.$_POST["duracao"].' dias  ");</script>';
												echo '<script>window.location.href="listarferias.php?cpf='.$_POST["cpf"].'";</script>';
												exit;	
											}
											if($jatemferiasa == 1)
											{
												$conn->close();		
												echo '<script>window.alert("Cliente CPF '.$_POST['cpf'].' já possui férias programadas em uma data posterior à data escolhida '.$_POST["dataferias"].' \n Verifique as datas programadas e escolha uma superior ou exclua esta superior primeiro.  ");</script>';
												echo '<script>window.location.href="listarferias.php?cpf='.$_POST["cpf"].'";</script>';
												exit;	
											}											
											
									}
								}
								
							}
					}				
				}


			
				try
				{
					$sql_insert = "insert into ferias 
									(cpfCliente , duracaoFerias, dataferias) 
									values
									('".str_replace(".","",str_replace("-","",$_POST['cpf']))."',".$_POST['duracao'].",STR_TO_DATE('".$_POST["dataferias"]."', '%d/%m/%Y'))";
			
					if(mysqli_query($conn, $sql_insert))
					{
						$conn->close();		
						echo '<script>window.alert("Ferias para o CPF '.$_POST['cpf'].' cadastrado com sucesso");</script>';
						echo '<script>window.location.href="listarferias.php?cpf='.$_POST["cpf"].'";</script>';
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
		,duracaoFerias
		,dataFerias
		FROM ferias 
		where cpfCliente = '".$_REQUEST['cpf']."' 
		and dataFerias = '".$_REQUEST['data']."' 
		";

		  $result_lista = mysqli_query($conn, $sql_lista);
		  
	
		//MONTA NA TELA O CLIENTE PARA EDIÇÃO
		  while($row = mysqli_fetch_assoc($result_lista)) 
		  {
			$cpf 	  		= $row["cpfCliente"];
			$duracao 	  		= $row["duracaoFerias"];	
			$datapag    	= $row["dataFerias"];	
			$dataori 		= explode("-",$datapag); 
			$dataferias  =  $dataori[2]."/".$dataori[1]."/".$dataori[0];			
		
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
								//VERIFICA SE O CLIENTE TEM MAIS DE 30 DIAS DE FÉRIAS PROGRAMADOS
									$sql_consulta_dias_ferias_s = "SELECT duracaoFerias
									FROM ferias where cpfCliente = '".str_replace(".","",str_replace("-","",$_POST['cpf']))."' 
									and dataFerias <> STR_TO_DATE('".$_POST["dataferias"]."', '%d/%m/%Y') ";
	
									$result_dias_ferias_s = mysqli_query($conn, $sql_consulta_dias_ferias_s);
														
									while($rowdfs = mysqli_fetch_assoc($result_dias_ferias_s)) 
									{
										$duracaoFeriass = $duracaoFeriass + $rowdfs["duracaoFerias"];
									}										
									$duracaoagregada = $_POST["duracao"] + $duracaoFeriass;
									if($duracaoagregada > 30)
									{
										$conn->close();		
										echo '<script>window.alert("Cliente CPF '.$_POST['cpf'].' já possui 30 dias de férias programados no total ou somados ao que está sendo editado agora");</script>';
										echo '<script>window.location.href="listarferias.php?cpf='.$_POST["cpf"].'";</script>';
										exit;	
									}
								

				try
				{
					$sql_update = "update ferias 
									set 
									duracaoFerias = '".$_POST['duracao']."' 
									where cpfCliente = '".str_replace(".","",str_replace("-","",$_POST['cpf']))."' 
									and dataFerias =  STR_TO_DATE('".$_POST["dataferias"]."', '%d/%m/%Y') 
									";

					if(mysqli_query($conn, $sql_update))
					{
						$conn->close();		
						echo '<script>window.alert("ferias para o CPF '.$_POST['cpf'].' atualizado com sucesso");</script>';
						echo '<script>window.location.href="listarferias.php?cpf='.$_POST["cpf"].'";</script>';
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
			$dataferias  =  $dataori[2]."/".$dataori[1]."/".$dataori[0];		
				try
				{
					$sql_update = "delete from ferias 
									where cpfCliente = '".str_replace(".","",str_replace("-","",$_REQUEST['cpf']))."' 
									and dataFerias =  STR_TO_DATE('".$dataferias."', '%d/%m/%Y') 
					";


					if(mysqli_query($conn, $sql_update))
					{
						$conn->close();		
						echo '<script>window.alert("ferias com CPF '.$_REQUEST['cpf'].' excluído com sucesso");</script>';
						echo '<script>window.location.href="listarferias.php?cpf='.$_REQUEST["cpf"].'";</script>';
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
    <title>Academia de Gin&aacute;stica · Férias</title>

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
  <p class="h1"><?php if($_REQUEST['acao'] == 'edicao') { ?> Editar <?php } else {   ?> Cadastrar <?php } ?>F&eacute;rias</p>
<?php 
if($_REQUEST['acao'] == 'edicao') 
{ 
?> 
  <form name="cadastro" action="ferias.php?acao=atualizar" method="post" enctype="multipart/form-data" onSubmit="return verificaCamposFerias();">
<?php  
}
else
{
?>
  <form name="cadastro" action="ferias.php?acao=gravar" method="post" enctype="multipart/form-data" onSubmit="return verificaCamposFerias();">	
<?php 
}
?>
        
    <div class="form-group">
    <label for="inputCPF">CPF</label>
    <input type="text" class="form-control" id="cpf" name="cpf" value="<?php echo trim($_REQUEST["cpf"]);?> " readonly>
  </div>
    
    <div class="form-group">
    <label for="inputDataFerias">Data das Férias</label>
    <input type="text" class="form-control" name="dataferias" id="dataferias" maxlength="10" onkeypress="mascaraData( this, event )" <?php if ($dataferias != "") { ?>  value="<?php echo $dataferias;?>" readonly <?php } ?> placeholder="01/01/1900">
  </div>
    
  <div class="form-group">
    <label for="inputDiasSolicitados">Dias de Férias Solicitado</label>
    <input type="text" class="form-control" name="duracao" id="duracao" maxlength="2" <?php if($duracao!= ""){?> value="<?php echo $duracao;?>" <?php  } ?> placeholder="20">
  </div>
    
  
  <input class="btn btn-primary btn-lg" type="submit" value="Confirmar" >
  <a class="btn btn-secondary btn-lg" href="Javascript:history.back();" role="button">Cancelar</a>

</form>
<div class="error-message" id="errosform" style=" display:none;"></div>
        </div>
    </div>
        
</main><!-- /.container -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
      <script>window.jQuery || document.write('<script src="../assets/js/vendor/jquery.slim.min.js"><\/script>')</script><script src="../assets/dist/js/bootstrap.bundle.js"></script></body>
</html>
