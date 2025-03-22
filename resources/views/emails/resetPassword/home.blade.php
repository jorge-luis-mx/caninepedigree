<!DOCTYPE html>
<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" lang="en">

<head>
	<title></title>
	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@100;200;300;400;500;600;700;800;900" rel="stylesheet" type="text/css">
	<style>
		* {
			box-sizing: border-box;
		}

		body {
			margin: 0;
			padding: 0;
		}

		a[x-apple-data-detectors] {
			color: inherit !important;
			text-decoration: inherit !important;
		}

		#MessageViewBody a {
			color: inherit;
			text-decoration: none;
		}

		p {
			line-height: inherit
		}

		.desktop_hide,
		.desktop_hide table {
			mso-hide: all;
			display: none;
			max-height: 0px;
			overflow: hidden;
		}

		.image_block img+div {
			display: none;
		}

		sup,
		sub {
			font-size: 75%;
			line-height: 0;
		}

		#converted-body .list_block ul,
		#converted-body .list_block ol,
		.body [class~="x_list_block"] ul,
		.body [class~="x_list_block"] ol,
		u+.body .list_block ul,
		u+.body .list_block ol {
			padding-left: 20px;
		}

		@media (max-width:620px) {
			.desktop_hide table.icons-inner {
				display: inline-block !important;
			}

			.icons-inner {
				text-align: center;
			}

			.icons-inner td {
				margin: 0 auto;
			}

			.mobile_hide {
				display: none;
			}

			.row-content {
				width: 100% !important;
			}

			.stack .column {
				width: 100% !important;
				display: block;
				padding-left: 0px !important;
				padding-right: 0px !important;
				border-left: 30px solid #ffffff !important;
				border-right: 30px solid #ffffff !important;
			}

			.pad{
				padding-left: 30px !important;
				padding-right: 30px !important;
			}
			.pad .btn a{
				padding: 10px 30px !important;
			}

			.mobile_hide {
				min-height: 0;
				max-height: 0;
				max-width: 0;
				overflow: hidden;
				font-size: 0px;
			}

			.desktop_hide,
			.desktop_hide table {
				display: table !important;
				max-height: none !important;
			}
			.column-11 .pad{
				padding-left: 0px !important;
				padding-right: 0px !important;
			}
			.column-11 p{
				text-align: center !important;
			}

		}
		@media (max-width:320px) {
			.pad .btn a{
				padding: 10px 10px !important;
			}
		}
	</style>
</head>

<body class="body" style="background-color: #f7f7f7; margin: 0; padding-top: 50px; -webkit-text-size-adjust: none; text-size-adjust: none;">
	<table class="nl-container" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f7f7f7;">
		<tbody>
			<tr>
				<td>
                    <!-- Header -->
					@include('emails.resetPassword.header')
					
                    <!-- Content -->

					@include('emails.resetPassword.body') 
					
                    <!-- footer -->
					@include('emails.resetPassword.footer')
				</td>
			</tr>
		</tbody>
	</table><!-- End -->
</body>

</html>