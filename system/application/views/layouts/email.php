<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<style type="text/css">

body {
	margin: 0.5in;
	font-family: Helvetica, Arial, Verdana, sans-serif; /*Trebuchet MS,*/
	background: #eee;
	text-align: center;
}
#email_body
{
	width: 500px;
	padding: 15px;
	background: white;
	margin: 10px auto;
	text-align: left;
	border: solid 1px #ccc;
}
h1, h2, h3, h4, h5, h6, li, blockquote, p, th, td {
	font-family: Helvetica, Arial, Verdana, sans-serif; /*Trebuchet MS,*/
}
h1, h2, h3, h4 {
	color: #F89406;
	font-weight: normal;
}
h4, h5, h6 {
	color: #F89406;
}
h2 {
	margin: 0 auto auto auto;
	font-size: x-large;
}
h2 span {
	text-transform: uppercase;
}
h3 {
	margin: 10px auto auto auto;
}
li, blockquote, p, th, td {
	font-size: 80%;
}

a{
	color: #F89406;
}

.terms {
	font-size: small;
}

p.disclaimer {
	font-size: small;
	color: #bbb;
}
p.disclaimer a {
	color: #bbb;
}

p.pull-out
{
	padding: 5px;
	background: #F89406;
	color: #fff;
	font-size: 120%;
}

p.pull-out a
{
	color: #fff;
	font-weight: bold;
}


table {
	width: 100%;
	border-collapse: collapse;
	margin: 10px 0;
}

th{
	background: #eee;
}
th, td{
	padding: 3px 0;
	border-bottom: 1pt solid #ccc;
}
td p {
	font-size: small;
	margin: 0;
}
th {
	text-align: left;
}

.overview {
	border: solid 5px #ccc;
	margin-top: 10px;
}
#footer {
	margin-top: 10px;
	text-align: center;
	font-size: 6pt;
	color: #999999;
}
#footer a {
	color: #999999;
	text-decoration: none;
}
table.stripe {
	border-collapse: collapse;
	page-break-after: auto;
}
table.stripe td {
	border-bottom: 1pt solid black;
}
.section_break
{
	page-break-before: always;
} 

a img
{
	border: none;
}
</style>
</head>
<body>

<div id="email_body">

	<?php echo $template['body']; ?>

</div>

<div id="footer">
	<a href="http://bookyourbeds.com" title="Powered by Book Your Beds">Powered By BookYourBeds</a>
</div>

</body>
</html>
