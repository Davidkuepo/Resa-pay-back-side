<!DOCTYPE html>
<html>
<head>
    <title>Votre compte élève</title>
</head>
<body>
    <h1>Bonjour {{ $studentProfile->student_name }},</h1>
    <p>Votre compte a été créé avec succès sur la plateforme GuimsEduc.</p>
    <p>Voici vos identifiants pour vous connecter :</p>
    <ul>
        <li><strong>Identifiant :</strong> {{ $studentProfile->user->username }}</li>
        <li><strong>Mot de passe :</strong> {{ $password }}</li>
    </ul>
    <p>Nous vous recommandons de changer votre mot de passe après votre première connexion.</p>
    <p>Merci,</p>
    <p>L'équipe GuimsEduc</p>
</body>
</html>