
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SIBUCARA PRO') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1e293b;
        }

        .auth-page {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: #fff;
        }

        .auth-side {
            background: #f4f7f6;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px;
        }

        .auth-side-content {
            width: 100%;
            max-width: 480px;
        }

        .auth-brand {
            display: flex;
            align-items: center;
            gap: 13px;
            margin-bottom: 65px;
        }

        .auth-brand-icon {
            width: 48px;
            height: 48px;
            border-radius: 13px;
            background: #288140;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 23px;
        }

        .auth-brand-name {
            font-size: 23px;
            font-weight: 800;
        }

        .auth-green {
            color: #288140;
        }

        .auth-side h1 {
            font-size: 40px;
            line-height: 1.25;
            font-weight: 800;
            margin: 0 0 20px;
        }

        .auth-side p {
            color: #64748b;
            line-height: 1.8;
            font-size: 16px;
        }

        .auth-benefits {
            margin-top: 40px;
            display: grid;
            gap: 20px;
        }

        .auth-benefit {
            display: flex;
            align-items: center;
            gap: 15px;
            color: #475569;
            font-size: 14px;
        }

        .auth-benefit i {
            width: 43px;
            height: 43px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: white;
            color: #288140;
        }

        .auth-main {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 55px 35px;
        }

        .auth-card {
            width: 100%;
            max-width: 440px;
        }

        .auth-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 13px;
            border-radius: 6px;
            background: #eaf4eb;
            color: #288140;
            font-size: 11px;
            font-weight: 800;
            margin-bottom: 22px;
        }

        .auth-title {
            font-size: 30px;
            font-weight: 800;
            margin: 0 0 12px;
            color: #1e293b;
        }

        .auth-description {
            font-size: 13px;
            line-height: 1.8;
            color: #64748b;
            margin: 0 0 30px;
        }

        .auth-form {
            display: grid;
            gap: 20px;
        }

        .auth-field label,
        .auth-label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 9px;
        }

        .auth-input-wrap {
            position: relative;
        }

        .auth-input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
        }

        .auth-input {
            width: 100%;
            height: 49px;
            padding: 0 15px 0 43px;
            border: 1px solid #dbe3df;
            border-radius: 9px;
            outline: none;
            font: inherit;
            font-size: 13px;
            color: #334155;
            background: white;
        }

        .auth-input:focus {
            border-color: #288140;
            box-shadow: 0 0 0 3px rgba(40,129,64,.10);
        }

        .auth-password {
            padding-right: 45px;
        }

        .auth-eye {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            border: 0;
            background: transparent;
            color: #94a3b8;
            cursor: pointer;
        }

        .auth-error {
            color: #dc2626;
            font-size: 12px;
            margin-top: 6px;
        }

        .auth-between {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }

        .auth-small-link {
            font-size: 12px;
            color: #288140;
            font-weight: 700;
            text-decoration: none;
        }

        .auth-remember {
            display: flex;
            align-items: center;
            gap: 9px;
            font-size: 12px;
            color: #64748b;
        }

        .auth-remember input,
        .auth-terms input {
            accent-color: #288140;
        }

        .auth-button {
            width: 100%;
            height: 50px;
            border: 0;
            border-radius: 9px;
            background: #288140;
            color: white;
            font-family: inherit;
            font-size: 13px;
            font-weight: 800;
            cursor: pointer;
            transition: .2s;
        }

        .auth-button:hover {
            background: #206833;
        }

        .auth-divider {
            display: flex;
            align-items: center;
            gap: 15px;
            margin: 27px 0;
            color: #94a3b8;
            font-size: 12px;
        }

        .auth-divider::before,
        .auth-divider::after {
            content: "";
            height: 1px;
            flex: 1;
            background: #e2e8f0;
        }

        .auth-bottom-link {
            min-height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e2e8f0;
            border-radius: 9px;
            color: #64748b;
            font-size: 12px;
            text-decoration: none;
        }

        .auth-bottom-link strong {
            color: #288140;
            margin-left: 4px;
        }

        .auth-note {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-top: 28px;
            color: #94a3b8;
            font-size: 10px;
            text-align: center;
        }

        .auth-note i {
            color: #288140;
        }

        .auth-role-list {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .auth-role {
            display: block;
            cursor: pointer;
        }

        .auth-role input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .auth-role-content {
            min-height: 105px;
            border: 1px solid #dbe3df;
            border-radius: 10px;
            padding: 13px 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-align: center;
            color: #64748b;
            font-size: 11px;
        }

        .auth-role-content i {
            font-size: 19px;
            color: #288140;
        }

        .auth-role input:checked + .auth-role-content {
            border: 2px solid #288140;
            background: #f0faf2;
            color: #288140;
        }

        .auth-terms {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            font-size: 12px;
            color: #64748b;
        }

        @media (max-width: 900px) {
            .auth-page {
                grid-template-columns: 1fr;
            }

            .auth-side {
                display: none;
            }

            .auth-main {
                padding: 45px 25px;
            }

            .auth-card {
                max-width: 500px;
            }
        }

        @media (max-width: 480px) {
            .auth-main {
                padding: 30px 20px;
            }

            .auth-title {
                font-size: 26px;
            }

            .auth-role-list {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    {{ $slot }}
</body>
</html>
