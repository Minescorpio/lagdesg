<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nouveau message de contact</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #2E324A;
            color: white;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
        }
        .field {
            margin-bottom: 15px;
        }
        .label {
            font-weight: bold;
            color: #2E324A;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9em;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Nouveau message de contact</h1>
    </div>

    <div class="content">
        <div class="field">
            <p class="label">Nom :</p>
            <p>{{ $data['name'] }}</p>
        </div>

        <div class="field">
            <p class="label">Email :</p>
            <p>{{ $data['email'] }}</p>
        </div>

        <div class="field">
            <p class="label">Sujet :</p>
            <p>{{ $data['subject'] }}</p>
        </div>

        <div class="field">
            <p class="label">Message :</p>
            <p>{{ $data['message'] }}</p>
        </div>
    </div>

    <div class="footer">
        <p>Ce message a été envoyé depuis le formulaire de contact de La Grotte des Geeks</p>
        <p>© {{ date('Y') }} La Grotte des Geeks - Tous droits réservés</p>
    </div>
</body>
</html> 