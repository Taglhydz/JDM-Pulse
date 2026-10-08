# Build le front React et l'envoie dans le public/ de Laravel sur AlwaysData
# Usage, depuis la racine du dépôt :
#   powershell -ExecutionPolicy Bypass -File scripts\deploy-front.ps1 -Compte <compte>
param([Parameter(Mandatory = $true)][string]$Compte)

$ErrorActionPreference = "Stop"
$hote   = "$Compte@ssh-$Compte.alwaysdata.net"
$public = "jdmpulse/laravel-jdmpulse/public"

npm --prefix react-jdmpulse run build
if ($LASTEXITCODE -ne 0) { throw "Le build React a échoué" }

# Une archive plutôt qu'une copie fichier par fichier : un seul transfert,
# et le .htaccess de Laravel déjà présent dans public/ n'est pas touché
tar -czf front.tgz -C react-jdmpulse/build .
if ($LASTEXITCODE -ne 0) { throw "La création de l'archive a échoué" }

scp front.tgz "${hote}:"
if ($LASTEXITCODE -ne 0) { Remove-Item front.tgz; throw "L'envoi de l'archive a échoué" }

# static/ est vidé avant extraction pour ne pas accumuler les anciens bundles
ssh $hote "rm -rf $public/static && tar -xzf front.tgz -C $public && rm front.tgz"
if ($LASTEXITCODE -ne 0) { Remove-Item front.tgz; throw "L'extraction sur le serveur a échoué" }

Remove-Item front.tgz
Write-Host "Front déployé : https://$Compte.alwaysdata.net"
