<!doctype html>
<html class="fixed js flexbox flexboxlegacy no-touch csstransforms csstransforms3d no-overflowscrolling webkit chrome win js no-mobile-device custom-scroll">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">

		<title>MFI | Software</title>
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<!-- Web Fonts  -->
		<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

		<!-- Vendor CSS -->

		<link rel="stylesheet" href="/assets/vendor/bootstrap/css/bootstrap.css" />
		<link rel="stylesheet" href="/assets/vendor/font-awesome/css/all.min.css" />
		<link rel="stylesheet" href="/assets/vendor/boxicons/css/boxicons.min.css" />
		<link rel="stylesheet" href="/assets/vendor/magnific-popup/magnific-popup.css" />
		<!-- <link rel="stylesheet" href="/assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css" /> -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />

		<link rel="stylesheet" href="/assets/vendor/datatables/media/css/dataTables.bootstrap5.css" />
		<link rel="stylesheet" href="/assets/vendor/select2/css/select2.css" />
		<link rel="stylesheet" href="/assets/vendor/select2-bootstrap-theme/select2-bootstrap.min.css" />
		<link rel="stylesheet" href="/assets/vendor/bootstrap-multiselect/css/bootstrap-multiselect.css" />

		<!-- Theme CSS -->
		<link rel="stylesheet" href="/assets/css/theme.css" />

		<!-- Skin CSS -->
		<link rel="stylesheet" href="/assets/css/skins/default.css" />

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="/assets/css/custom.css">
	</head>
	<style>
        /* Loader styles */
        .loader {
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
            /* background: rgba(255, 255, 255, 0.9);  */
			background-color: transparent;
        }
        .loader div {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
			/* background-color: transparent; */
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

