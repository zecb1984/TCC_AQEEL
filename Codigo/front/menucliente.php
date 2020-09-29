<?php
ini_set("display_errors", false);
ERROR_REPORTING(E_NONE);
$encoding = 'UTF-8';

require_once("../conexao.php");

if($_REQUEST['acao'] == "gravaraula")
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
                $sql_verifica_presenca = "SELECT 
                count(*) as quantaspresencas 
              FROM presenca a 
              where cpfCliente = '".str_replace(".","",str_replace("-","",$_POST['id']))."'
              and id_aula = ".$_POST["idaula"]."
              and diaSemana = '".$_POST["dataaula"]."' ";
          
              $result_qtpres  = mysqli_query($conn, $sql_verifica_presenca);
            
              while($rowpr = mysqli_fetch_assoc($result_qtpres)) 
              {//MONTA A LISTA PARA EXIBIR NA TELA
                 $quantaspresencas  = $rowpr["quantaspresencas"];	
              }
          
          
              if($quantaspresencas > 0 )
              {
          
                $conn->close();		
                echo '<script>window.alert("Cliente com CPF '.$_POST['id'].' já está com presença na aula neste dia da semana .");</script>';
                echo '<script>history.back();</script>';
          
                exit;
              }                         

                        try
                        {
                            $sql_insert = "insert into presenca 
                                            (nomeAula, cpfCliente , nomeInstrutor_0, diaSemana, id_aula) 
                                            values
                        (
                            '".mb_convert_case($_POST["nomedaaula"], MB_CASE_LOWER, $encoding)."'
                            ,'".str_replace(".","",str_replace("-","",$_POST['id']))."'
                            ,'".mb_convert_case($_POST['instrutor'], MB_CASE_LOWER, $encoding)."'
                            ,'".$_POST["dataaula"]."'
                            ,".$_POST["idaula"]."
                        )";

                            if(mysqli_query($conn, $sql_insert))
                            {
                                $conn->close();		
                                echo '<script>window.alert("Aluno com o CPF '.$_POST['id'].' presente na aula '.$_POST["nomedaaula"].'  com sucesso.\n Verifique com o instrutor");</script>';
                                echo '<script>self.close();</script>';
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
else if ($_REQUEST['acao'] == "marcar")
{


        

            // Create connection
            $conn = mysqli_connect($servidor, $usuario, $senha, $banco);

            // Check connection
            if (!$conn) 
            {
                    die("Falha na Conexão: " . mysqli_connect_error());
            }
            else
            {	//VERIFICA SE FOI DIGITADO UM PARÂMETRO DE PESQUISA. SE NÃO, LISTA TODOS OS CLIENTES
                $sql_consulta = "SELECT count(*) as quantos FROM aula ";
                
                if(trim($_POST["aula"]) != "") 
                { //CONSULTA O CLIENTE PELO O NOME OU PELO CPF
                $sql_consulta .= "	where lower(nomeAula) like '%".mb_strtolower($_POST["aula"],'UTF-8')."%' ";
                }		
                
                    
                $result = mysqli_query($conn, $sql_consulta);
                    
                while($row = mysqli_fetch_assoc($result)) 
                {
                $quantos = $row["quantos"];
                }		

                $ids = array();
                $aulas 		   = array();
                $instrutores   = array();
                $periodosi      = array();
                $periodosf      = array();        
                $dias          = array();
                
                $sql_lista = "SELECT 
                id_aula
                , cpfInstrutor 
                ,nomeAula
                ,nomeInstrutor_0
                ,horarioInicio
                ,horarioFim
                ,diasDaSemana
                FROM aula ";


                    if(trim($_POST["aula"]) != "") 
                    { //CONSULTA A AULA PELO NOME 
                    $sql_lista .= "	where lower(nomeAula) like '%".mb_strtolower($_POST["aula"],'UTF-8')."%' ";
                    }	
                                
            
                $result_lista = mysqli_query($conn, $sql_lista);
                
                while($row = mysqli_fetch_assoc($result_lista)) 
            {//MONTA A LISTA PARA EXIBIR NA TELA
                $ids[]       = $row["id_aula"];
                $aulas[] 	    = $row["nomeAula"];
                $instrutores[] 	= $row["nomeInstrutor_0"];			
                $periodosi[] 	= $row["horarioInicio"];
                $periodosf[]    = $row["horarioFim"];            
                $dias[]         = $row["diasDaSemana"];			
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
            <title>Academia de Ginastica · Aula</title>

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
        <form name="cadastro" action="menucliente.php?acao=confirmar&cpf=<?php echo $_POST["cpfvalidado"];?>" method="post" target="_blank" enctype="multipart/form-data" onSubmit="return verificaCamposCliente();">	
        <main role="main" class="container">
        <div class="jumbotron">
            <div class="col-sm-8 mx-auto">
        <p class="h1">Listar Aulas</p>

        <table class="table">
            <thead class="thead-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nome</th>
                <th scope="col">Instrutor</th>
                <th scope="col">Período</th>
                <th scope="col">Dias da Semana</th>        
            </tr>
            </thead>
            <tbody>
            <?php 
        if($quantos > 0)	
        {
            for($i=0;$i<count($aulas);$i++)
            {	//MONTA O GRID CONFORME OS DIAS DESTA AULA
                    $perinis = explode(" ",$periodosi[$i]);
                    $periodo_sel_ini = substr($perinis[1],0,5);
                    $perfims = explode(" ",$periodosf[$i]);
                    $periodo_sel_fim = substr($perfims[1],0,5);   
                    
                    $diassel = explode(" ",$dias[$i]);
                    $diasaula = "";
                    for($j=0;$j<20;$j++)
                    {

                        
                        if(trim($diassel[$j]) == "1")
                        {
                            $diasaula = " Segunda <br>";
                        }
                        if(trim($diassel[$j]) == "2")
                        {
                            $diasaula .= " Ter&ccedil;a <br>";
                        }
                        if(trim($diassel[$j]) == "3")
                        {
                            $diasaula .= " Quarta <br>";
                        }
                        if(trim($diassel[$j]) == "4")
                        {
                            $diasaula .= " Quinta <br>";
                        }
                        if(trim($diassel[$j]) == "5")
                        {
                            $diasaula .= " Sexta <br>";
                        }
                        if(trim($diassel[$j]) == "6")
                        {
                            $diasaula .= " S&aacute;bado <br>";
                        } 
                    }
        ?>		
            <tbody>
            <tr>
                <th scope="row">
                <input type="radio" name="selecao" value="<?php echo $ids[$i];?>" id="selecao"  />
                </th>
                <td><?php echo $aulas[$i];?></td>
                <td><?php echo $instrutores[$i];?></td>
                <td><?php echo $periodo_sel_ini." a ".$periodo_sel_fim;?></td>
                <td><?php echo $diasaula;?></td>		
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
            </tbody>
        </table>
        <?php if ($quantos >0) 
        {
        ?>
        <input type="submit" class="btn btn-primary btn-lg"  value="Confirmar">
        <?php 
        }
        ?>
        <a class="btn btn-secondary btn-lg" href="JavaScript:history.back();" role="button">Cancelar</a>
        <a class="btn btn-secondary btn-lg" href="/indexFront.html" role="button">Sair</a>
            </div>
            </div>
        </form>    
        </main><!-- /.container -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
            <script>window.jQuery || document.write('<script src="../assets/js/vendor/jquery.slim.min.js"><\/script>')</script><script src="../assets/dist/js/bootstrap.bundle.js"></script></body>

<?php
}
else if($_REQUEST['acao'] == "confirmar")
{


if($_REQUEST["selecao"] == "")
{
       echo '<script>window.alert("Selecione uma aula.");</script>';
       echo '<script>self.close();</script>';
       exit;	
}	

            $conn = mysqli_connect($servidor, $usuario, $senha, $banco);

            //////PREPARA A LISTA DE CLIENTES

            $cpf_clientes = array();
            $clientes = array();

            // Check connection
            if (!$conn) 
            {
                die("Falha na Conexão: " . mysqli_connect_error());
            }
            else
            {
                $sql_cliente = "SELECT 
                cpfCliente
                ,nomeCliente
                FROM cliente where cpfCliente = '".str_replace("-","",str_replace(".","",$_REQUEST["cpf"]))."'
                order by 2 ";

                    
                $result_cliente  = mysqli_query($conn, $sql_cliente);

                while($row = mysqli_fetch_assoc($result_cliente)) 
                {//MONTA A LISTA PARA EXIBIR NA TELA
                $cpf_cliente  = $row["cpfCliente"];			     
                $cliente      = $row["nomeCliente"];			      
                }


                $sql_nome_aula = "SELECT nomeAula, nomeInstrutor_0 , horarioInicio, horarioFim, diasDaSemana from aula where id_aula =".$_REQUEST["selecao"];

                $result_nmaula  = mysqli_query($conn, $sql_nome_aula);

                while($row = mysqli_fetch_assoc($result_nmaula)) 
                {//MONTA A LISTA PARA EXIBIR NA TELA
                $nomeaula  = $row["nomeAula"];			        
                $instrutor  = $row["nomeInstrutor_0"];	
                $horarioInicio  = $row["horarioInicio"];	
                $horarioFim  = $row["horarioFim"];	                     
                $diaSemana  = $row["diasDaSemana"];	                  
                }    

                $horainia = explode(" ", $horarioInicio);
                $horainiaula = $horainia[1];
                $horainif = explode(" ", $horarioFim);
                $horafimaula = $horainif[1];            
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
                <title>Academia de Gin&aacute;stica · Lista de Presença da aula <?php echo $nomeaula;?> para o cliente <?php echo $cliente;?></title>
            
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
            <p class="h1"><?php if($_REQUEST['acao'] == 'edicao') { ?> Editar <?php } else {   ?> Cadastrar <?php } ?> Presença da Aula <?php echo $nomeaula;?></p>
            <?php 
            if($_REQUEST['acao'] == 'edicao') 
            { 
            ?> 
            <form name="cadastro" action="presenca.php?acao=atualizar" method="post" enctype="multipart/form-data" onSubmit="return verificaCamposPres();">
            <?php  
            }
            else
            {
            ?>
            <form name="cadastro" action="menucliente.php?acao=gravaraula" method="post" enctype="multipart/form-data" onSubmit="return verificaCamposPres();">	
            <?php 
            }
            ?>
                
            <div class="form-group">
                <label for="inputAula">Nome da Aula</label>
                <input type="text" class="form-control" id="aula" name="aula" maxlength="10"  value="<?php echo $nomeaula." - Das ".$horainiaula." a ".$horafimaula;?>" readonly  placeholder="Body Dance">
            </div>
            <div class="form-group">
            <div class="input-group-prepend">
            <label class="input-group-text" for="inputGroupSelect01">Dia da Aula</label>
                     
                 <select class="custom-select" id="dataaula" name="dataaula">
                <option value="">Selecione</option>
                <?php 
                $dia = explode(" ", $diaSemana);

                for($i=0;$i<count($dia);$i++)
                {
                switch(trim($dia[$i]))
                {
                    case "1" : $dia_da_semana = "Segunda"; break;
                    case "2" : $dia_da_semana = "Terça"; break;
                    case "3" : $dia_da_semana = "Quarta"; break;
                    case "4" : $dia_da_semana = "Quinta"; break;
                    case "5" : $dia_da_semana = "Sexta"; break;
                    case "6" : $dia_da_semana = "Sábado"; break;               

                }
                if(trim($dia[$i])!= "")
                {
                ?>
                <option value="<?php echo $dia[$i];?>" >    
                <?php echo $dia_da_semana; ?>
                </option>
                <?php
                }
                }
                ?>
                </select>   
                </div>                  
            </div>
            <div class="form-group">
            <label for="inputAula">Nome do Instrutor</label>
                <input type="text" class="form-control" id="instrutor" name="instrutor" maxlength="10"  value="<?php echo $instrutor;?>" readonly  placeholder="Body Dance">
            </div>  
                
                <div class="input-group mb-3">
                <label class="input-group-text" for="inputGroupSelect01">Cliente</label>
                <input type="text" class="custom-select" id="cliente" name="cliente" value="<?php echo $cliente;?>" readonly>
                </div>    
                <input type="hidden" name="id" value="<?php echo $_REQUEST["cpf"];?>" >
                <input type="hidden" name="idaula" value="<?php echo $_REQUEST["selecao"];?>" >    
                <input type="hidden" name="nomedaaula" value="<?php echo $nomeaula;?>" >   

                <input class="btn btn-primary btn-lg" type="submit" value="Confirmar" >
                <a class="btn btn-secondary btn-lg" href="Javascript:window.close();" role="button">Cancelar</a>    
            </form>
            <div class="error-message" id="errosform" style=" display:none;"></div>	
                </div>
                </div>
            </main><!-- /.container -->
            <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
                <script>window.jQuery || document.write('<script src="../assets/js/vendor/jquery.slim.min.js"><\/script>')</script><script src="../assets/dist/js/bootstrap.bundle.js"></script></body>
            </html>

<?php	
}
else if($_REQUEST["acao"] == "listarpresencas")
{

            $cliente = str_replace("-","",str_replace(".","",$_REQUEST['cpf']));

            require_once("../conexao.php");
        

            // Create connection
            $conn = mysqli_connect($servidor, $usuario, $senha, $banco);

            // Check connection
            if (!$conn) 
            {
                    die("Falha na Conexão: " . mysqli_connect_error());
            }
            else
            {	//VERIFICA SE FOI DIGITADO UM PARÂMETRO DE PESQUISA. SE NÃO, LISTA TODOS OS CLIENTES
                $sql_consulta = "SELECT count(*) as quantos FROM presenca 
                            	where cpfCliente = ".$cliente." ";
            		
            
        
                
                    
                $result = mysqli_query($conn, $sql_consulta);
                    
                while($row = mysqli_fetch_assoc($result)) 
                {
                $quantos = $row["quantos"];
                }		

                    $ids = array();
                        $aulas 		   = array();
                        $instrutores   = array();
                    $periodosi      = array();
                        $alunos      = array();        
                    $dias          = array();
                    $nmalunos     = array();
                        
                        $sql_lista = "SELECT 
                        a.id_presenca
                    ,a.cpfCliente
                    ,b.nomeCliente
                        ,a.nomeAula
                    ,a.nomeInstrutor_0
                    ,a.diaSemana
                    FROM presenca a
                    inner join cliente b on a.cpfCliente = b.cpfCliente 
                    where b.cpfCliente = ".$cliente." ";
                            
            

            
                $result_lista = mysqli_query($conn, $sql_lista);
                
                while($row = mysqli_fetch_assoc($result_lista)) 
            {//MONTA A LISTA PARA EXIBIR NA TELA
            $ids[]       = $row["id_presenca"];
                    $aulas[] 	    = $row["nomeAula"];
            $instrutores[] 	= $row["nomeInstrutor_0"];		      	
            $periodosi[] 	= $row["diaSemana"];
            $alunos[]     = $row["cpfCliente"];
            $nmalunos[]   = $row["nomeCliente"];
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
            <title>Academia de Ginastica · Aula</title>

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
        <p class="h1">Listar Presença do Cliente <?php echo $_REQUEST["cpf"];?></p>

        <table class="table">
            <thead class="thead-dark">
            <tr>
                 <th scope="col">Nome Aula</th>
                <th scope="col">Instrutor</th>
                <th scope="col">Aluno Presente</th>
                <th scope="col">Dia da Aula</th>        
            </tr>
            </thead>
            <tbody>
            <?php 
        if($quantos > 0)	
        {
            for($i=0;$i<count($aulas);$i++)
            {	//MONTA O GRID CONFORME OS DIAS DESTA AULA
                

        ?>		
            <tbody>
            <tr>
                <td><?php echo $aulas[$i];?></td>
                <td><?php echo $instrutores[$i];?></td>
                <td><?php echo $nmalunos[$i]." - CPF - ".$alunos[$i];?></td>
                <?php
        switch(trim($periodosi[$i]))
        {
          case "1" : $dia_da_semana = "Segunda"; break;
          case "2" : $dia_da_semana = "Terça"; break;
          case "3" : $dia_da_semana = "Quarta"; break;
          case "4" : $dia_da_semana = "Quinta"; break;
          case "5" : $dia_da_semana = "Sexta"; break;
          case "6" : $dia_da_semana = "Sábado"; break;   
        }

        ?>
        <td><?php echo $dia_da_semana;?></td>			
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
            </tbody>
        </table>
        
        <?php 
        if($quantos > 0) 
        {
        ?>
            <div id="botoesacao"></div>  
        <?php
        }
        ?>	  
        <a class="btn btn-secondary btn-lg" href="Javascript:history.back();" role="button">Voltar</a>
            </div>
            </div>
        </main><!-- /.container -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
            <script>window.jQuery || document.write('<script src="../assets/js/vendor/jquery.slim.min.js"><\/script>')</script><script src="../assets/dist/js/bootstrap.bundle.js"></script></body>
        </html>
        <?php

}
else
{
        ?>
        <!doctype html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <meta name="description" content="">
            <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
            <meta name="generator" content="Jekyll v4.0.1">
            <title>Academia de Ginastica · Home</title>

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

            .inputcpf {

                display: block;
                height: calc(1.5em + .75rem + 2px);
                padding: .375rem .75rem;
                font-size: 2rem;
                font-weight: 400;
                line-height: 1.5;
                color: #495057;
                background-color: #fff;
                background-clip: padding-box;
                border: 1px solid #ced4da;
                border-radius: .25rem;
                transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
                text-align: center;
            }


            </style>
            <!-- Custom styles for this template -->
            <link href="starter-template.css" rel="stylesheet">
        </head>
        <body>


        <main role="main" class="container">

        <div class="starter-template">
            <h1>Bem vindo!</h1>
            <p class="lead">Sistema da Academia de Ginástica.</p>
        </div>

            <center>
            <form name="cadastro" action="menucliente.php?acao=marcar" method="post" enctype="multipart/form-data" onSubmit="return verificaCamposCliente();">	
            
            <div class="form-group">


            </div>
                                                    

            <input type="hidden" name="cpfvalidado" id="cpfvalidado" value="<?php echo $_POST["cpfvalidado"];?>">
                <input class="btn btn-primary btn-lg" type="submit" value="Marcar Aula" > <br><br>
                <input class="btn btn-primary btn-lg" type="button" onClick="Javascript:window.location.href='menucliente.php?acao=listarpresencas&cpf=<?php echo $_POST["cpfvalidado"];?>';" value="Histórico de Presenças" > <br><br><br><br>
				<a class="btn btn-secondary btn-lg" href="/indexFront.html" role="button">Sair</a>
            </div>

            </form>
                <div class="error-message" id="errosform" style=" display:none;"></div>	
                </center>
            

        </main><!-- /.container -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
            <script>window.jQuery || document.write('<script src="../assets/js/vendor/jquery.slim.min.js"><\/script>')</script><script src="../assets/dist/js/bootstrap.bundle.js"></script></body>
        </html>

<?php 
}
?>
