# EXAM AGENDA 

1. `` composer install ``
   
   **A cause d'un bug** de symfony actuellement (il y une issues sur le depot officiel de symfony), j'ai été obligé de forcer 
   doctrine/doctrine-bundle en version 2.2.4 et non pas en 2.3.0 dans le fichier **composer.json** j'espère que cela va pas géné quand tu vas recupérer le dépot.
   ``"doctrine/doctrine-bundle": "2.2.4",`` 

2. `` php bin/console doctrine:database:create ``

3. `` php bin/console doctrine:migrations:migrate ``

4. `` php bin/console doctrine:fixtures:load ``

5. l'administrateur login: 'admin@gmail.com' et mp: 'admin'
6. les editeurs login leur email et mp: 'password'
7. ou crée un nouvel utilisateur

Bundle utilisé :
1. webpack encore
2. twigpack
3. CKeditor