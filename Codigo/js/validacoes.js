// JavaScript Document


function tecladoNumerico(valor)
{

	if(valor == 'a')
	{	
		var str= $("#cpf").val();
		var position = document.getElementById('cpf').selectionStart-1;
	
		str = str.substr(0, position) + '' + str.substr(position + 1);
		$("#cpf").val(str);
	}
	else if(valor == 'l')
	{	
		document.getElementById("cpf").value = "";
		document.getElementById("retorno").style.display = "none";	
		document.getElementById("botaocliente").style.display = "none";	
		document.getElementById('cpfvalidado').value = "";
	}	
	else
	{
		if(document.getElementById("cpf").value.length <= 13)
		{
			document.getElementById("cpf").value += valor;
			MascaraCPF(document.getElementById("cpf"));
		}
	}

}




//adiciona mascara de cnpj
function MascaraCNPJ(cnpj){
        if(mascaraInteiro(cnpj)==false){
                event.returnValue = false;
        }       
        return formataCampo(cnpj, '00.000.000/0000-00', event);
}

//adiciona mascara de cep
function MascaraCep(cep){
                if(mascaraInteiro(cep)==false){
                event.returnValue = false;
        }       
        return formataCampo(cep, '00.000-000', event);
}

//adiciona mascara de data
function MascaraData(data){
        if(mascaraInteiro(data)==false){
                event.returnValue = false;
        }       
        return formataCampo(data, '00/00/0000', event);
}

//adiciona mascara ao telefone
function MascaraTelefone(tel){  
        if(mascaraInteiro(tel)==false){
                event.returnValue = false;
        }       
        return formataCampo(tel, '(00) 0000-0000', event);
}

//adiciona mascara ao CPF
function MascaraCPF(cpf){
        if(mascaraInteiro(cpf)==false){
                event.returnValue = false;
        }       
        return formataCampo(cpf, '000.000.000-00', event);
}

//adiciona mascara ao RG
function MascaraRG(rg){
        if((rg)==false){
                event.returnValue = false;
        }       
        return formataCampo(rg, '00.000.000-0', event);
}

//valida telefone
function ValidaTelefone(tel){
        exp = /\(\d{2}\)\ \d{4}\-\d{4}/
        if(!exp.test(tel.value))
                alert('Numero de Telefone Invalido!');
}

//valida CEP
function ValidaCep(cep){
        exp = /\d{2}\.\d{3}\-\d{3}/
        if(!exp.test(cep.value))
                alert('Numero de Cep Invalido!');               
}


//valida o CPF digitado
function ValidarCPF(Objcpf){
	/*  
	var cpf = Objcpf.value;
        exp = /\.|\-/g
        cpf = cpf.toString().replace( exp, "" ); 
        var digitoDigitado = eval(cpf.charAt(9)+cpf.charAt(10));
        var soma1=0, soma2=0;
        var vlr =11;

        for(i=0;i<9;i++){
                soma1+=eval(cpf.charAt(i)*(vlr-1));
                soma2+=eval(cpf.charAt(i)*vlr);
                vlr--;
        }       
        soma1 = (((soma1*10)%11)==10 ? 0:((soma1*10)%11));
        soma2=(((soma2+(2*soma1))*10)%11);

        var digitoGerado=(soma1*10)+soma2;
        if(digitoGerado!=digitoDigitado)        
				alert('CPF Invalido!');         
				*/
				return true;
}

//valida numero inteiro com mascara
function mascaraInteiro(){
        if (event.keyCode < 48 || event.keyCode > 57){
                event.returnValue = false;
                return false;
        }
        return true;
}

//valida o CNPJ digitado
function ValidarCNPJ(ObjCnpj){
        var cnpj = ObjCnpj.value;
        var valida = new Array(6,5,4,3,2,9,8,7,6,5,4,3,2);
        var dig1= new Number;
        var dig2= new Number;

        exp = /\.|\-|\//g
        cnpj = cnpj.toString().replace( exp, "" ); 
        var digito = new Number(eval(cnpj.charAt(12)+cnpj.charAt(13)));

        for(i = 0; i<valida.length; i++){
                dig1 += (i>0? (cnpj.charAt(i-1)*valida[i]):0);  
                dig2 += cnpj.charAt(i)*valida[i];       
        }
        dig1 = (((dig1%11)<2)? 0:(11-(dig1%11)));
        dig2 = (((dig2%11)<2)? 0:(11-(dig2%11)));

        if(((dig1*10)+dig2) != digito)  
                alert('CNPJ Invalido!');

}

//formata de forma generica os campos
function formataCampo(campo, Mascara, evento) { 
        var boleanoMascara; 

        var Digitato = evento.keyCode;
        exp = /\-|\.|\/|\(|\)| /g
        campoSoNumeros = campo.value.toString().replace( exp, "" ); 

        var posicaoCampo = 0;    
        var NovoValorCampo="";
        var TamanhoMascara = campoSoNumeros.length;; 

        if (Digitato != 8) { // backspace 
                for(i=0; i<= TamanhoMascara; i++) { 
                        boleanoMascara  = ((Mascara.charAt(i) == "-") || (Mascara.charAt(i) == ".")
                                                                || (Mascara.charAt(i) == "/")) 
                        boleanoMascara  = boleanoMascara || ((Mascara.charAt(i) == "(") 
                                                                || (Mascara.charAt(i) == ")") || (Mascara.charAt(i) == " ")) 
                        if (boleanoMascara) { 
                                NovoValorCampo += Mascara.charAt(i); 
                                  TamanhoMascara++;
                        }else { 
                                NovoValorCampo += campoSoNumeros.charAt(posicaoCampo); 
                                posicaoCampo++; 
                          }              
                  }      
                campo.value = NovoValorCampo;
                  return true; 
        }else { 
                return true; 
        }
}

function verificaConsultaCliente()
{
	
	var nome = document.getElementById("cliente").value;	
	if(nome.length != 0)
	{
		if(nome.length < 5)
		{
			window.alert("Preencha pelo menos 5 caracteres na pesquisa");
			return false;					
		}
		else
		{
			return true;
		}
	}	
}

function verificaConsultaInstrutor()
{
	
	var nome = document.getElementById("instrutor").value;	
	if(nome.length != 0)
	{
		if(nome.length < 5)
		{
			window.alert("Preencha pelo menos 5 caracteres na pesquisa");
			return false;					
		}
		else
		{
			return true;
		}
	}	
}


function verificaCamposCliente()

{
	var erro = 0;
	var nome = document.getElementById("nome").value;
	var rg = document.getElementById("rg").value;
	rg = rg.replace('-','');
	rg = rg.replace('.','');
	var cpf = document.getElementById("cpf").value;
	var endereco = document.getElementById("endereco").value;
	var mensagem = "";
	if (nome=="")
	{
		erro = 1;
		mensagem += "Campo nome em branco<br>";
	}
	if (rg=="")
	{
		erro = 1;
		mensagem += "Campo RG em branco<br>";
	}
	else
	{
		if(isNaN(rg))
		{
			erro = 1;
			mensagem += "Campo RG só pode ter números<br>";			
		}
	}
	if (cpf=="")
	{
		erro = 1;
		mensagem += "Campo CPF em branco<br>";
	}
	if (endereco=="")
	{
		erro = 1;
		mensagem += "Campo Endereço em branco<br>";
	}	
	
	
	if(erro == 1)
	{
		document.getElementById("errosform").style.display = "block";
		document.getElementById("errosform").innerHTML = "<span class=\"error-text\">Por favor , corrija os seguintes campos:<br>" + mensagem + "</span><br>";
		return false;
		
	}
	else
	{		
				document.getElementById("errosform").style.display = "none";
				document.getElementById("errosform").innerHTML = "";
				return true;		
	}
	
	
}


function verificaCamposInstrutor()

{
	var erro = 0;
	var nome = document.getElementById("nome").value;
	var rg = document.getElementById("rg").value;
	rg = rg.replace('-','');
	rg = rg.replace('.','');
	var cpf = document.getElementById("cpf").value;
	var atividade = document.getElementById("atividade").value;
	var mensagem = "";
	if (nome=="")
	{
		erro = 1;
		mensagem += "Campo nome em branco<br>";
	}
	if (rg=="")
	{
		erro = 1;
		mensagem += "Campo RG em branco<br>";
	}
	else
	{
		if(isNaN(rg))
		{
			erro = 1;
			mensagem += "Campo RG só pode ter números<br>";			
		}
	}
	if (cpf=="")
	{
		erro = 1;
		mensagem += "Campo CPF em branco<br>";
	}
	if (atividade=="")
	{
		erro = 1;
		mensagem += "Campo Atividade em branco<br>";
	}	
	
	
	if(erro == 1)
	{
		document.getElementById("errosform").style.display = "block";
		document.getElementById("errosform").innerHTML = "<span class=\"error-text\">Por favor , corrija os seguintes campos:<br>" + mensagem + "</span><br>";
		return false;
		
	}
	else
	{		
				document.getElementById("errosform").style.display = "none";
				document.getElementById("errosform").innerHTML = "";
				return true;		
	}
	
	
}



function preencheBotoesConsultaCliente(valor)

{
	document.getElementById("botoesacao").innerHTML = '<a class="btn btn-info btn-lg" href="../1_Cliente/2_Pagamento/listarpagamento.php?cpf='+valor+'" role="button">Pagamento</a>';	
	document.getElementById("botoesacao").innerHTML += '<a class="btn btn-info btn-lg" href="../1_Cliente/3_Ferias/listarferias.php?cpf='+valor+'" role="button">Férias</a>';	
	document.getElementById("botoesacao").innerHTML += '<a class="btn btn-info btn-lg" href="../1_Cliente/4_Avaliacao/listaravaliacao.php?cpf='+valor+'" target="_blank" role="button">Avaliação</a>';		
	document.getElementById("botoesacao").innerHTML += '<a class="btn btn-primary btn-lg" href="../1_Cliente/1_Cliente/cliente.php?acao=edicao&cpf='+valor+'" role="button">Editar</a>';
	document.getElementById("botoesacao").innerHTML += '  <a class="btn btn-primary btn-lg" href="../1_Cliente/1_Cliente/cliente.php?acao=excluir&cpf='+valor+'" role="button">Deletar</a>';

}


function preencheBotoesConsultaClienteFisio(valor)

{
	document.getElementById("botoesacao").innerHTML += '<a class="btn btn-info btn-lg" href="../1_Cliente/4_Avaliacao/listaravaliacao.php?cpf='+valor+'" target="_blank" role="button">Avaliação</a>';		
}



function preencheBotoesConsultaInstrutor(valor)

{
	document.getElementById("botoesacao").innerHTML = '  <a class="btn btn-primary btn-lg" href="instrutor.php?acao=edicao&cpf='+valor+'" role="button">Editar</a> ';
	document.getElementById("botoesacao").innerHTML += '  <a class="btn btn-primary btn-lg" href="instrutor.php?acao=exclusao&cpf='+valor+'" onClick="if(!confirm(\'Deseja realmente excluir este registro?\')) return false;" role="button">Deletar</a>';
}


function preencheBotoesConsultaPagto(cpf,data)
{
	document.getElementById("botoesacao").innerHTML = '  <a class="btn btn-primary btn-lg" href="pagamento.php?acao=edicao&cpf='+cpf+'&data='+data+'" role="button">Editar</a> ';
	document.getElementById("botoesacao").innerHTML += '  <a class="btn btn-primary btn-lg" href="pagamento.php?acao=exclusao&cpf='+cpf+'&data='+data+'" onClick="if(!confirm(\'Deseja realmente excluir este registro?\')) return false;" role="button">Deletar</a>';	
}


function preencheBotoesConsultaFerias(cpf,data)
{
	document.getElementById("botoesacao").innerHTML = '  <a class="btn btn-primary btn-lg" href="ferias.php?acao=edicao&cpf='+cpf+'&data='+data+'" role="button">Editar</a> ';
	document.getElementById("botoesacao").innerHTML += '  <a class="btn btn-primary btn-lg" href="ferias.php?acao=exclusao&cpf='+cpf+'&data='+data+'" onClick="if(!confirm(\'Deseja realmente excluir este registro?\')) return false;" role="button">Deletar</a>';	
}

function preencheBotoesConsultaAvaliar(cpf,id)
{
	document.getElementById("botoesacao").innerHTML = '  <a class="btn btn-primary btn-lg" href="avaliacao.php?acao=edicao&cpf='+cpf+'&id='+id+'" role="button">Editar</a> ';
	document.getElementById("botoesacao").innerHTML += '  <a class="btn btn-primary btn-lg" href="avaliacao.php?acao=exclusao&cpf='+cpf+'&id='+id+'" onClick="if(!confirm(\'Deseja realmente excluir este registro?\')) return false;" role="button">Deletar</a>';	
}


function preencheBotoesConsultaAula(id)
{
	document.getElementById("botoesacao").innerHTML = '  <a class="btn btn-primary btn-lg" href="aula.php?acao=edicao&id='+id+'" role="button">Editar</a> ';
	document.getElementById("botoesacao").innerHTML += '  <a class="btn btn-primary btn-lg" href="aula.php?acao=exclusao&id='+id+'" onClick="if(!confirm(\'Deseja realmente excluir este registro?\')) return false;" role="button">Deletar</a>';	
	document.getElementById("botoesacao").innerHTML += '  <a class="btn btn-info btn-lg" href="listarpresenca.php?idaula='+id+'" role="button">Presenças</a>';		
}


function preencheBotoesConsultaPres(id, idaula)
{
	document.getElementById("botoesacao").innerHTML = '  <a class="btn btn-primary btn-lg" href="presenca.php?acao=edicao&id='+id+'" role="button">Editar</a> ';
	document.getElementById("botoesacao").innerHTML += '  <a class="btn btn-primary btn-lg" href="presenca.php?acao=exclusao&id='+id+'&idaula='+idaula+'" onClick="if(!confirm(\'Deseja realmente excluir este registro?\')) return false;" role="button">Deletar</a>';	
}



function preencheBotoesConsultaRel(id)
{
	if(id == "a")
	{
	document.getElementById("botoesacao").innerHTML = '  <a class="btn btn-primary btn-lg" href="listarclientesmatriculados.php" role="button">Gerar</a> ';
	}
	if(id == "i")
	{
	document.getElementById("botoesacao").innerHTML += '  <a class="btn btn-primary btn-lg" href="listarclientesinadimplentes.php" role="button">Gerar</a>';	
	}
}






function verificaCamposPagto(valor)
{
	var erro = 0;
	var plano = document.getElementById("plano").value;
	var datapagamento = document.getElementById("datapagamento").value;
	var situacaopagamento = document.getElementById("situacaopagamento").value;	
	
	var mensagem = "";
	if (plano=="")
	{
		erro = 1;
		mensagem += "Selecione um plano<br>";
	}	
	
	if (datapagamento=="")
	{
		erro = 1;
		mensagem += "Campo Data de Pagamento em branco<br>";	
	}
	
	if (situacaopagamento=="")
	{
		erro = 1;
		mensagem += "Selecione se está pago ou não<br>";	
	}		

	if(erro == 1)
	{
		document.getElementById("errosform").style.display = "block";
		document.getElementById("errosform").innerHTML = "<span class=\"error-text\">Por favor , corrija os seguintes campos:<br>" + mensagem + "</span><br>";
		return false;
		
	}
	else
	{		
				document.getElementById("errosform").style.display = "none";
				document.getElementById("errosform").innerHTML = "";
				return true;		
	}	
	
	
}




function verificaCamposFerias(valor)
{
	var erro = 0;
	var duracao = document.getElementById("duracao").value;
	var dataferias = document.getElementById("dataferias").value;
	
	var mensagem = "";
	if (duracao=="")
	{
		erro = 1;
		mensagem += "Campo Dias de Férias Solicitado em branco<br>";
	}	
	
	if (dataferias=="")
	{
		erro = 1;
		mensagem += "Campo Data de Férias em branco<br>";	
	}		

	if(erro == 1)
	{
		document.getElementById("errosform").style.display = "block";
		document.getElementById("errosform").innerHTML = "<span class=\"error-text\">Por favor , corrija os seguintes campos:<br>" + mensagem + "</span><br>";
		return false;
		
	}
	else
	{		
				document.getElementById("errosform").style.display = "none";
				document.getElementById("errosform").innerHTML = "";
				return true;		
	}	
	
	
}


function verificaCamposAvalia(valor)
{
	var erro = 0;
	var anamnese = document.getElementById("anamnese").value;
	var medidas = document.getElementById("medidas").value;
	var ergometrico = document.getElementById("ergometrico").value;	
	
	
	var mensagem = "";
	if (anamnese=="")
	{
		erro = 1;
		mensagem += "Campo Anamnese do Paciente em branco<br>";
	}	
	
	if (medidas=="")
	{
		erro = 1;
		mensagem += "Campo Exame de Dobras Cutâneas em branco<br>";	
	}		

	if (ergometrico=="")
	{
		erro = 1;
		mensagem += "Campo Exame Ergométrico em branco<br>";	
	}			

	if(erro == 1)
	{
		document.getElementById("errosform").style.display = "block";
		document.getElementById("errosform").innerHTML = "<span class=\"error-text\">Por favor , corrija os seguintes campos:<br>" + mensagem + "</span><br>";
		return false;
		
	}
	else
	{		
				document.getElementById("errosform").style.display = "none";
				document.getElementById("errosform").innerHTML = "";
				return true;		
	}	
	
	
}



function verificaCamposAula(valor)
{
	var erro = 0;
	var aula = document.getElementById("aula").value;
	var horainicio = document.getElementById("horainicio").value;
	var horafim = document.getElementById("horafim").value;	
	var segunda = document.getElementById("segunda").checked;		
	var terca = document.getElementById("terca").checked;		
	var quarta = document.getElementById("quarta").checked;		
	var quinta = document.getElementById("quinta").checked;		
	var sexta = document.getElementById("sexta").checked;		
	var sabado = document.getElementById("sabado").checked;							
	var instrutor = document.getElementById("instrutor").value;	


	
	var mensagem = "";
	if (aula=="")
	{
		erro = 1;
		mensagem += "Campo Aula em branco<br>";
	}	
	
	if (horainicio=="")
	{
		erro = 1;
		mensagem += "Campo Horário do Início em branco<br>";	
	}	
	else
	{
		if(isNaN(horainicio.replace(':','')))	
		{
			erro = 1;
			mensagem += "Digite apenas números na Hora do Início<br>";				
		}
		else
		{
			if(horainicio.substring(0,2) > 23)
			{
				erro = 1;
				mensagem += "Digite Hora do Início entre 00 e 23<br>";	
			}
			if(horainicio.substring(3,5) > 59)
			{
				erro = 1;
				mensagem += "Digite Minutos do Início entre 00 e 59<br>";				
			}				

		}

	}
	
	if (horafim=="")
	{
		erro = 1;
		mensagem += "Campo Horário do Fim em branco<br>";	
	}
	else
	{
		if(isNaN(horainicio.replace(':','')))	
		{
			erro = 1;
			mensagem += "Digite apenas números na Hora do Fim<br>";				
		}
		else
		{
			if(horafim.substring(0,2) > 23)
			{
				erro = 1;
				mensagem += "Digite Hora do Fim entre 00 e 23<br>";	
			}
			if(horafim.substring(3,5) > 59)
			{
				erro = 1;
				mensagem += "Digite Minutos do Fim entre 00 e 59<br>";				
			}			
			
		}		

	}		
	
	
	if(horainicio != "" && horafim != "")
	{
		if(horainicio ==  horafim)
		{
			erro = 1;
			mensagem += "Horário do início não pode ser igual ao Horário do fim<br>";	

		}
		else
		{
			if(horainicio.substring(0,2)  > horafim.substring(0,2))
			{
			 erro = 1;
			 mensagem += "Horário do início não pode ser maior que o Horário do fim<br>";	
			}
			if(horainicio.substring(0,2)  == horafim.substring(0,2))
			{
				if(horainicio.substring(3,5)  > horafim.substring(3,5))
				{
					 erro = 1;
					 mensagem += "Minutos do início não podem ser maior que Minutos do fim quando horários são iguais<br>";	
				}
			}
			var hrinicio = parseInt(horainicio.substring(0,2));
			var hrfim = parseInt(horafim.substring(0,2));			
			var soma = hrfim - hrinicio	;
			if(soma > 1)
			{
				erro = 1;
				mensagem += "Duração da aula só pode ser no máximo de 1 hora<br>";	
			}
			else
			{
				if(soma == 1)
				{
					if(horafim.substring(3,5)  > horainicio.substring(3,5))
					{
						erro = 1;
						mensagem += "Duração da aula só pode ser no máximo de 1 hora<br>";	
					}
				}				
			}
			if(hrinicio >= 23)
			{
				erro = 1;
				mensagem += "Aulas só podem ser marcadas com início no máximo até às 22h <br>";	
			}

		}
   	   
	}


	if (segunda==false && terca==false && quarta==false && quinta==false && sexta==false && sabado==false)
	{
		erro = 1;
		mensagem += "Selecione pelo menos um dia da semana da aula<br>";	
	}		
	
	if (instrutor=="")
	{
		erro = 1;
		mensagem += "Selecione pelo menos um instrutor da aula<br>";	
	}		

	if(erro == 1)
	{
		document.getElementById("errosform").style.display = "block";
		document.getElementById("errosform").innerHTML = "<span class=\"error-text\">Por favor , corrija os seguintes campos:<br>" + mensagem + "</span><br>";
		return false;
		
	}
	else
	{		
				document.getElementById("errosform").style.display = "none";
				document.getElementById("errosform").innerHTML = "";
				return true;		
	}	
	
	
}


function verificaCamposPres(valor)
{
	var erro = 0;
	var dataaula = document.getElementById("dataaula").value;
	var cliente = document.getElementById("cliente").value;
	
	var mensagem = "";
	if (dataaula=="")
	{
		erro = 1;
		mensagem += "Campo Data da Aula em branco <br>";
	}	
	
	if (cliente=="")
	{
		erro = 1;
		mensagem += "Selecione um Cliente<br>";	
	}		

	if(erro == 1)
	{
		document.getElementById("errosform").style.display = "block";
		document.getElementById("errosform").innerHTML = "<span class=\"error-text\">Por favor , corrija os seguintes campos:<br>" + mensagem + "</span><br>";
		return false;
		
	}
	else
	{		
				document.getElementById("errosform").style.display = "none";
				document.getElementById("errosform").innerHTML = "";
				return true;		
	}	
	
	
}


function mascaraData( campo, e )
{
	var kC = (document.all) ? event.keyCode : e.keyCode;
	var data = campo.value;
	
	if( kC!=8 && kC!=46 )
	{
		if( data.length==2 )
		{
			campo.value = data += '/';
		}
		else if( data.length==5 )
		{
			campo.value = data += '/';
		}
		else
			campo.value = data;
	}
}


function mascaraHora(campo, e)
{
  if (!e)
    return false;

  car = (window.Event) ? e.which : e.keyCode;

  if (car == 8)
    return true;

  if((((car >=48)&&(car <=57))||(car == 8)) && (campo.value.length < 7))
  {
    if (campo.value.length == 2)
      campo.value = campo.value + ':';

//    campo.value = campo.value + ':';

      return true;
    }
    return false;
}






// Variável que receberá o objeto XMLHttpRequest
var req;
function validarDados(campo, valor) {
// Verificar o Browser
// Firefox, Google Chrome, Safari e outros
if(window.XMLHttpRequest) {
   req = new XMLHttpRequest();
}
// Internet Explorer
else if(window.ActiveXObject) {
   req = new ActiveXObject("Microsoft.XMLHTTP");
}
// Aqui vai o valor e o nome do campo que pediu a requisição.
var url = "back/1_Cliente/1_Cliente/cliente.php?acao=acesso&cpf="+valor;
// Chamada do método open para processar a requisição
req.open("Get", url, true);
// Quando o objeto recebe o retorno, chamamos a seguinte função;
req.onreadystatechange = function() {
    // Exibe a mensagem "Verificando" enquanto carrega
    if(req.readyState == 1) {
		document.getElementById("retorno").style.display = "block";	
        document.getElementById('retorno').innerHTML = '<font color="gray">Verificando...</font>';
    }
    // Verifica se o Ajax realizou todas as operações corretamente (essencial)
    if(req.readyState == 4 && req.status == 200) {
		document.getElementById("retorno").style.display = "block";	
					
    // Resposta retornada pelo cliente.php
    var resposta = req.responseText;
	// Abaixo colocamos a resposta na div do campo que fez a requisição
		if (resposta == 'n')
		{
			document.getElementById('retorno').innerHTML = "<font color=red><b><h1>Acesso Negado , falar com a recep&ccedil;&atilde;o</h1></b></font>";
		}
		else if (resposta == 's')
		{
			document.getElementById('retorno').innerHTML = "<font color=green><b><h1>Acesso Liberado</h1></b></font>";	
			document.getElementById("botaocliente").style.display = "block";	
			document.getElementById('cpfvalidado').value = valor;				
		}

    
    }
}
req.send(null);
}