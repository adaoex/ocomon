<script type="text/javascript">
<!--
    //Fun��es javascript

	var GLArray = new Array();

	function popup(pagina)	{ //Exibe uma janela popUP
		x = window.open(pagina,'Gr�fico','dependent=yes,width=800,height=600,scrollbars=yes,statusbar=no,resizable=no');
		x.moveTo(10,10);

		return false
	}

	function popupS(pagina)	{ //Exibe uma janela popUP
		x = window.open(pagina,'Gr�fico','dependent=yes,width=800,height=600,scrollbars=yes,statusbar=no,resizable=no');
		x.moveTo(10,10);

		return false
	}

	function popupWH(pagina,larg,altur)	{ //Exibe uma janela popUP
		x = window.open(pagina,'Gr�fico','dependent=yes,width='+(larg+20)+',height='+(altur+20)+',scrollbars=no,statusbar=no,resizable=no');
		x.moveTo(10,10);

		return false
	}


	function popup_alerta(pagina)	{ //Exibe uma janela popUP
		x = window.open(pagina,'_blank','dependent=yes,width=600,height=400,scrollbars=yes,statusbar=no,resizable=yes');

		x.moveTo(window.parent.screenX+50, window.parent.screenY+50);
		return false
	}

	function popup_wide(pagina)	{ //Exibe uma janela popUP
		x = window.open(pagina,'_blank','dependent=yes,width=600,height=200,scrollbars=yes,statusbar=no,resizable=yes');

		x.moveTo(window.parent.screenX+50, window.parent.screenY+50);
		return false
	}

	function mini_popup(pagina)	{ //Exibe uma janela popUP
		x = window.open(pagina,'_blank','dependent=yes,width=400,height=250,scrollbars=yes,statusbar=no,resizable=yes');
		x.moveTo(window.parent.screenX+50, window.parent.screenY+50);
		return false
	}

	function popup_alerta_mini(pagina)	{ //Exibe uma janela popUP
		x=window.open(pagina,'_blank','dependent=yes,width=400,height=250,scrollbars=yes,statusbar=no,resizable=yes');
		x.moveTo(100,100);
		x.moveTo(window.parent.screenX+50, window.parent.screenY+50);
		return false
	}


	function popup_alerta_wide(pagina)	{ //Exibe uma janela popUP
		x = window.open(pagina,'_blank','dependent=yes,width=800,height=400,scrollbars=yes,statusbar=no,resizable=yes');
		x.moveTo(window.parent.screenX+50, window.parent.screenY+50);
		return false
	}


	function mensagem(msg){
		alert(msg);
		return false
	}


	function redirect(url){
		window.location.href=url;
	}

	function redirectLoad(url, id){
		var obj = document.getElementById(id);
		window.location.href=url+obj.value;
	}

	function submitForm (obj) {
		obj.form.submit();
	}

	function reloadUrl(url, param){
		var obj = document.getElementById(id);
		window.location.href=url+param;
	}

	//criar acesso ao submit de excluir
	function confirma(msg,url){
		if (confirm(msg)){
			redirect(url);
		}
	}


	function confirmaAcao (msg, url, param){ //variavel php
		if (confirm(msg)){
			url += '?'+param;
			redirect(url);
		}
		return false;
	}


	function cancelLink () {
		return false;
	}

	function disableLink (link) {
		if (link.onclick)
			link.oldOnClick = link.onclick;
		link.onclick = cancelLink;
		if (link.style)
			link.style.cursor = 'default';
	}

	function enableLink (link) {
		link.onclick = link.oldOnClick ? link.oldOnClick : null;
		if (link.style)
			link.style.cursor =
			document.all ? 'hand' : 'pointer';
	}
	function toggleLink (link) {
	  if (link.disabled)
		enableLink (link)
	  else
		disableLink (link);
	  link.disabled = !link.disabled;
	}

	function desabilitaLinks(permissao){
		if (permissao!=1) {
			for (i=0; i<(document.links.length); i++) {
				toggleLink (document.links[i]);
			}
		}
	}

	function par(n) {
		var na = n;
		var nb = (na / 2);
		nb = Math.floor(nb);
		nb = nb * 2;
		if ( na == nb ) {
			return(1);
		} else {
			return(0);
		}
	}


	function corNatural(id) {//F8F8F1
		var obj = document.getElementById(id);

		var args = corNatural.arguments.length;
		//var id = destaca.arguments[0];
		if (args==1){
			//var color = "#CCCCFF";
			var color = "";
		} else
		if (args == 2)
			var color = corNatural.arguments[1];
		else
		if (args == 3){
			var color = corNatural.arguments[1];
			var color2 = corNatural.arguments[2];
		}


		//obj.style.background = obj.getAttributeNode('cN').value; /* Para ser usado lendo propriedade cN='cor' do objeto */
		if (navigator.userAgent.indexOf('MSIE') !=-1){ //M$ IE
			var classe = obj.getAttributeNode('class').value;
			obj.style.background = color;
			//var classe = obj.className;
		} else {
			//var classe ='';
			var classe = obj.getAttributeNode('class').value;
		}

		if ( classe != '') {
			//if ( classe == 'lin_par'  ) {  obj.style.background = 'url("../../includes/css/header_bar.gif")';  } else //'#EAE6D0'//

			//if ( classe == 'lin_par'  ) {  obj.style.background = '#E3E1E1';  } else
			//if ( classe == 'lin_impar' ) { obj.style.background = '#F6F6F6' ;}

			if ( classe == 'lin_par'  ) {  obj.style.background = color;  } else
			if ( classe == 'lin_impar' ) { obj.style.background = color2 ;}


		}
		//else { obj.style.background = '' }
		else { obj.style.background = color; }
	}

		function listItems()
		{
			var items = listItems.arguments.length
			document.write("<UL>\n")
			for (i = 0;i < items;i++)
			{
				document.write("<LI>" + listItems.arguments[i] + "\n")
			}
			document.write("</UL>\n")
		}

		function setBGColor(id){
			var obj = document.getElementById(id);

			if (obj.value!="IMG_DEFAULT")
				obj.style.background="";
			obj.style.backgroundColor = obj.value;

			return false;
		}

		function destaca(){

			var args = destaca.arguments.length;
			var id = destaca.arguments[0];

			if (args==1){
				//var color = "#CCCCFF";
				var color = "";
			} else
				var color = destaca.arguments[1];

			if ( verificaArray('', id) == false ) {
				var obj = document.getElementById(id);
				//obj.style.background = '#CCCCFF';// #CCFFCC #C7C8C6 #A3A352 '#D5D5D5'  #CCFFCC   #FDFED8
				obj.style.background = color;
			}
		}

		function libera(id){

			var args = libera.arguments.length;
			//var id = destaca.arguments[0];
			if (args==1){
				var color = "";
			} else
			if (args == 2)
			{
				var color = libera.arguments[1];
			} else
			if (args == 3) {
				var color = libera.arguments[1];
				var color2 = libera.arguments[2];
			} else
				var color2 = '';


			if ( verificaArray('', id) == false ) {
				var obj = document.getElementById(id);
				//obj.style.background = '';
				corNatural(id,color,color2); /* retorna � cor natural */
			}
		}


		function marca(){
			var args = marca.arguments.length;
			var id = marca.arguments[0];

			var obj = document.getElementById(id);
			if (args==1){
				//var color = "#FFCC99";
				var color = "";
			} else
				var color = marca.arguments[1];


			if ( verificaArray('', id) == false ) {
				verificaArray('marca', id)

				//obj.style.background = '#FFCC99';
				obj.style.background = color;
			} else {
				verificaArray('desmarca', id)
				//obj.style.background = '';
				destaca(id);
			}

		}

		function verificaArray(acao, id) {
			var i;
			var tamArray = GLArray.length;
			var existe = false;

			for(i=0; i<tamArray; i++) {
				if ( GLArray[i] == id ) {
					existe = true;
					break;
				}
			}

			if ( (acao == 'marca') && (existe==false) ) {
				GLArray[tamArray] = id;
			} else if ( (acao == 'desmarca') && (existe==true) ) {
				var temp = new Array(tamArray-1); //-1
				var pos = 0;
				for(i=0; i<tamArray; i++) {
					if ( GLArray[i] != id ) {
						temp[pos] = GLArray[i];
						pos++;
					}
				}

				GLArray = new Array();
				var pos = temp.length;
				for(i=0; i<pos; i++) {
					GLArray[i] = temp[i];
				}
			}

			return existe;
		}

	function loadDefaultValue(id, valor){
		var obj = document.getElementById(id);
		obj.value = valor;
		return false;
	}



function validaForm(id,tipo,campo,obrigatorio){
	var regINT = /^[1-9]\d*$/; //express�o para validar numeros inteiros n�o iniciados com zero
	var regINTFULL = /^\d*$/; //express�o para validar numeros inteiros quaisquer
	var regDATA = /^((0?[1-9]|[12]\d)\/(0?[1-9]|1[0-2])|30\/(0?[13-9]|1[0-2])|31\/(0?[13578]|1[02]))\/(19|20)?\d{2}$/;
	var regDATA_ = /^((0?[1-9]|[12]\d)\-(0?[1-9]|1[0-2])|30\-(0?[13-9]|1[0-2])|31\-(0?[13578]|1[02]))\-(19|20)?\d{2}$/;
	var regDATAHORA = /^(((0?[1-9]|[12]\d)\/(0?[1-9]|1[0-2])|30\/(0?[13-9]|1[0-2])|31\/(0?[13578]|1[02]))\/(19|20)?\d{2})[ ]([0-1]\d|2[0-3])+:[0-5]\d:[0-5]\d$/;
	var regEMAIL = /^[\w!#$%&'*+\/=?^`{|}~-]+(\.[\w!#$%&'*+\/=?^`{|}~-]+)*@(([\w-]+\.)+[A-Za-z]{2,6}|\[\d{1,3}(\.\d{1,3}){3}\])$/;

	var regMULTIEMAIL = /^([\w!#$%&'*+\/=?^`{|}~-]+(\.[\w!#$%&'*+\/=?^`{|}~-]+)*@(([\w-]+\.)+[A-Za-z]{2,6}|\[\d{1,3}(\.\d{1,3}){3}\]))(\,\s?([\w!#$%&'*+\/=?^`{|}~-]+(\.[\w!#$%&'*+\/=?^`{|}~-]+)*@(([\w-]+\.)+[A-Za-z]{2,6}|\[\d{1,3}(\.\d{1,3}){3}\]))+)*$/;

	var regMOEDA = /^\d{1,3}(\.\d{3})*\,\d{2}$/;
	var regMOEDASIMP = /^\d*\,\d{2}$/;
	var regETIQUETA = /^[1-9]\d*(\,\d+)*$/; //express�o para validar consultas separadas por v�rgula;
	var regALFA = /^[A-Z]|[a-z]([A-Z]|[a-z])*$/;
	var regALFANUM = /^([A-Z]|[a-z]|[0-9])([A-Z]|[a-z]|[0-9])*\.?([A-Z]|[a-z]|[0-9])([A-Z]|[a-z]|[0-9])*$/; //Valores alfanum�rias aceitando separa��o com no m�ximo um ponto.
	var regALFAFULL = /^[\w!#$%&'*+\/=?^`{|}~-]+(\.[\w!#$%&'*+\/=?^`{|}~-]+)*$/;
	//var regFone = /^([\d]*([-]|[\s])?[\d]+)+([,][\s][\d]*([-]|[\s])?[\d]+)*$/;
	var regFone = /^(([+][\d]{2,2})?([-]|[\s])?[\d]*([-]|[\s])?[\d]+)+([,][\s]([+][\d]{2,2})?([-]|[\s])?[\d]*([-]|[\s])?[\d]+)*$/;
	var regCor = /^([#]([A-F]|[a-f]|[\d]){6,6})|([I][M][G][_][D][E][F][A][U][L][T])$/;
	//var d = document.cadastro;

	var obj = document.getElementById(id);
	var valor = obj.getAttributeNode('name').value;

	//alert (obj);

	//verificar se est� preenchido


	if ((obj.value == "")&&(obrigatorio==1)){
		alert("O campo [" + campo + "] deve ser preenchido!");
		obj.focus();
		return false;
	}



	if ((tipo == "INTEIRO")&&(obj.value != "")) {
		//validar dados num�ricos
		if (!regINT.test(obj.value)){
			alert ("O campo "+ campo +" deve conter apenas numeros inteiros n�o iniciados por ZERO!");
			obj.focus();
			return false;
		}
	} else

	if ((tipo == "COMBO")&&(obj.value != "")) {
		//validar dados num�ricos
		if (!regINT.test(obj.value)){
			alert ("O campo "+ campo +" deve ser selecionado!");
			obj.focus();
			return false;
		}
	} else

	if ((tipo == "INTEIROFULL")&&(obj.value != "")) {
		//validar dados num�ricos
		if (!regINTFULL.test(obj.value)){
			alert ("O campo "+ campo +" deve conter apenas numeros inteiros!");
			obj.focus();
			return false;
		}
	} else

	if ((tipo == "DATA")&&(obj.value != "")) {
		//validar data
		if (!regDATA.test(obj.value)){
			alert("Formato de data invalido! dd/mm/aaaa");
			obj.focus();
			return false;
			}
	} else

	if ((tipo == "DATA-")&&(obj.value != "")) {
		//validar data
		if (!regDATA_.test(obj.value)){
			alert("Formato de data invalido! dd-mm-aaaa");
			obj.focus();
			return false;
			}
	} else
	if ((tipo == "DATAHORA")&&(obj.value != "")) {
		//validar data
		if (!regDATAHORA.test(obj.value)){
			alert("Formato de data invalido! dd/mm/aaaa HH:mm:ss");
			obj.focus();
			return false;
			}
	} else


	if ((tipo == "EMAIL")&&(obj.value != "")){
		//validar email(verificao de endereco eletr�nico)
		if (!regEMAIL.test(obj.value)){
			alert("Formato de e-mail inv�lido!");
			obj.focus();
			return false;
		}
	} else

	if ((tipo == "MULTIEMAIL")&&(obj.value != "")){
		//validar email(verificao de endereco eletr�nico)
		if (!regMULTIEMAIL.test(obj.value)){
			alert("Formato de e-mail inv�lido! \"E-MAIL, E-MAIL\"");
			obj.focus();
			return false;
		}
	} else


	if ((tipo == "MOEDA")&&(obj.value != "")){
		//validar valor monet�rio
		if (!regMOEDA.test(obj.value)){
			alert("Formato de moeda inv�lido!");
			obj.focus();
			return false;
		}
	} else

	if ((tipo == "MOEDASIMP")&&(obj.value != "")){
		//validar valor monet�rio
		if (!regMOEDASIMP.test(obj.value)){
			alert("Formato de moeda inv�lido! XXXXXX,XX");
			obj.focus();
			return false;
		}
	} else

	if ((tipo == "ETIQUETA")&&(obj.value != "")){
		//validar valor monet�rio
		if (!regETIQUETA.test(obj.value)){
			alert("o Formato deve ser de valores inteiros n�o iniciados por Zero e separados por v�rgula!");
			obj.focus();
			return false;
		}
	}	else

	if ((tipo == "ALFA")&&(obj.value != "")){
		//validar valor monet�rio
		if (!regALFA.test(obj.value)){
			alert("Esse campo s� aceita carateres do alfabeto sem espa�os!");
			obj.focus();
			return false;
		}
	}	else

	if ((tipo == "ALFANUM")&&(obj.value != "")){
		//validar valor monet�rio
		if (!regALFANUM.test(obj.value)){
			alert("Esse campo s� aceita valores alfanum�ricos sem espa�os ou separados por um ponto(no m�ximo um)!");
			obj.focus();
			return false;
		}
	}

	if ((tipo == "ALFAFULL")&&(obj.value != "")){
		//validar valor monet�rio
		if (!regALFAFULL.test(obj.value)){
			alert("Esse campo s� aceita valores alfanum�ricos sem espa�os!");
			obj.focus();
			return false;
		}
	}

	if ((tipo == "FONE")&&(obj.value != "")){
		//validar valor monet�rio
		if (!regFone.test(obj.value)){
			alert("Esse campo s� aceita valores formatados para telefones (algarismos, tra�os e espa�os) separados por v�rgula.");
			obj.focus();
			return false;
		}
	}
	if ((tipo == "COR")&&(obj.value != "")){
		//validar valor monet�rio
		if (!regCor.test(obj.value)){
			alert("Esse campo s� aceita valores formatados para cores HTML! Ex: #FFCC99");
			obj.focus();
			return false;
		}
	}


	return true;
}

	function exibeEscondeImg(obj) {
		var item = document.getElementById(obj);
		if (item.style.display=='none'){
			item.style.display='';
		} else {
			item.style.display='none';
		}
	}

	function exibeEscondeHnt(obj) {

/*		if (document.all) {
			document.this.x.value=window.event.clientX;
			document.this.y.value=window.event.clientY;
		}
		else if (document.layers) {
			document.this.x.value=e.pageX;
			document.this.y.value=e.pageY;
		}*/


		if (document.all) {
			var x = window.event.clientX;
			var y = window.event.clientY;
		} else if (document.layers) {
			var x = pageX;
			var y = pageY;
		}

		var item = document.getElementById(obj);
		if (item.style.display=='none'){
			item.style.display='';
			item.style.top = y;
		} else {
			item.style.display='none';
		}
	}


	function invertView(id) {
		var element = document.getElementById(id);
		var elementImg = document.getElementById('img'+id);
		var address = '../../includes/icons/';

		if (element.style.display=='none'){
			element.style.display='';
			elementImg.src = address+'close.png';
		} else {
			element.style.display='none';
			elementImg.src = address+'open.png';
		}
	}




	function addEvent( id, type, fn ) {
		var obj = document.getElementById(id);

		if ( obj.attachEvent ) {
			obj['e'+type+fn] = fn;
			obj[type+fn] = function(){obj['e'+type+fn]( window.event );}
			obj.attachEvent( 'on'+type, obj[type+fn] );
		} else
			obj.addEventListener( type, fn, false );
	}

	function removeEvent( id, type, fn ) {
		var obj = document.getElementById(id);
		if ( obj.detachEvent ) {
			obj.detachEvent( 'on'+type, obj[type+fn] );
			obj[type+fn] = null;
		} else
			obj.removeEventListener( type, fn, false );
	}


	function Mouse() {
		var isIE = document.all;
		var ns6  = document.getElementById && !document.all;
		var ieTB = (document.compatMode && document.compatMode!="BackCompat")?document.documentElement:document.body;
		var px = null;
		var py = null;


		this.setEvent = function(e) {
			px = (ns6)?e.pageX:event.clientX+ieTB.scrollLeft;
			py = (ns6)?e.pageY:event.clientY+ieTB.scrollTop;
		}

		this.x = function() { return px; }

		this.y = function() { return py; }
	}

	function mouseMoveManager(e) {
		mouse.setEvent(e);
		//document.title = "Cursor_x: "+mouse.x()+" | Cursor_y: "+mouse.y();
	}

	function fecha()
	{
// 		if (history.back){
// 			return history.back();
// 		} else
// 			window.close();

		if (window.opener){
			return window.close();
		} else
			return history.back();
	}


	function showToolTip(e,text,id1, id2){
		if(document.all)e = event;

		var obj = document.getElementById(id1);
		var obj2 = document.getElementById(id2);
		obj2.innerHTML = text;
		obj.style.display = 'block';
		var st = Math.max(document.body.scrollTop,document.documentElement.scrollTop);
		if(navigator.userAgent.toLowerCase().indexOf('safari')>=0)st=0;
		var leftPos = e.clientX - 100;
		if(leftPos<0)leftPos = 0;
		obj.style.left = leftPos + 'px';
		obj.style.top = e.clientY - obj.offsetHeight -1 + st + 'px';
	}

	function hideToolTip(id)
	{
		document.getElementById(id).style.display = 'none';

	}

	function replaceAll( str, from, to ) {
		var idx = str.indexOf( from );
		while ( idx > -1 ) {
			str = str.replace( from, to );
			idx = str.indexOf( from );
		}
		return str;
	}

	function trim(str) {
		return str.replace(/^\s+|\s+$/g,"");
	}

	function foco(id){
		obj = document.getElementById(id);
		obj.focus();
		return true;
	}

	function ajaxFunction(div,script,divLoad){
		var ajaxRequest;  // The variable that makes Ajax possible!

		try{
			// Opera 8.0+, Firefox, Safari
			ajaxRequest = new XMLHttpRequest();
		} catch (e){
			// Internet Explorer Browsers
			try{
				ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
				try{
					ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e){
					// Something went wrong
					alert("Your browser broke!");
					return false;
				}
			}
		}
		// Create a function that will receive data sent from the server
		ajaxRequest.onreadystatechange = function(){
			if(ajaxRequest.readyState == 4){
				document.getElementById(divLoad).style.display = 'none';
				var ajaxDisplay = document.getElementById(div);
				ajaxDisplay.innerHTML = ajaxRequest.responseText;
			} else {
				document.getElementById(divLoad).style.display = '';
			}
		}

		var args = ajaxFunction.arguments.length;
		var i;
		var j;
		var array = new Array();

		for (i=3; i<args; i++){//Jogando os argumentos (apartir do terceiro pois os tres primeiros sao fixos) para um array
			j = i-3;
			array[j] = ajaxFunction.arguments[i];
		}

		var queryString = MontaQueryString(array);

		ajaxRequest.open("GET", script + queryString, true);
		ajaxRequest.send(null);
	}

	function MontaQueryString (array) {
		var i;
		var size = array.length;
		var queryString = '?';

		for (i=0; i<size; i++){
			var param = array[i].split('=');
			param[1] = document.getElementById(param[1]).value;

			queryString += param[0] + "=" + param[1] + "&";
		}
		return queryString;
	}


	function check_all(valor){
		
		with(document)
		{
			var d;
			d=document.getElementsByTagName("input");
			
			for(i=0;i<d.length;i++)
			{
				if(d[i].type=="checkbox")
				{
					d[i].checked=valor;
				}
			}
		}
	}


//-->
</script>