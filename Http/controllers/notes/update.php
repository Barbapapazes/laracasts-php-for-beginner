<?php

use Core\App;
use Core\Database;
use Core\Validator;

$db = App::resolve(Database::class);

$currentUser = 4;

$note = $db->query('select * from notes where id = :id', ['id' => $_POST['id']])->findOrFail();

authorize($note['user_id'] === $currentUser);

$errors = [];

if (!Validator::string($_POST['body'], 1, 1000)) {
  $errors['body'] = 'A body of no more than 1,000 characters is required.';
}

if (count($errors)) {
  return view('notes/edit.view.php', [
    'heading' => 'Edit Note',
    'note' => $note,
    'errors' => $errors
  ]);
}

$db->query('update notes set body = :body where id = :id', [
  'body' => $_POST['body'],
  'id' => $_POST['id']
]);

header('Location: /notes');
exit();
