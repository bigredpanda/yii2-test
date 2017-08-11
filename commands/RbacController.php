<?php

namespace app\commands;

use app\rbac\AuthorRule;
use Yii;
use yii\console\Controller;

/**
 * Class RbacController
 * @package app\commands
 */
class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAllRules();
        $auth->removeAllPermissions();

        $createUser = $auth->createPermission('createUser');
        $createUser->description = 'Create a user';
        $auth->add($createUser);

        $updateUser = $auth->createPermission('updateUser');
        $updateUser->description = 'Update user';
        $auth->add($updateUser);

        $deleteUser = $auth->createPermission('deleteUser');
        $deleteUser->description = 'Delete user';
        $auth->add($deleteUser);

        $viewUser = $auth->createPermission('viewUser');
        $viewUser->description = 'View list of users';
        $auth->add($viewUser);

        $createNote = $auth->createPermission('createNote');
        $createNote->description = 'Create a note';
        $auth->add($createNote);

        $viewNote = $auth->createPermission('viewNote');
        $viewNote->description = 'View a note';
        $auth->add($viewNote);


        $editAndDeleteNote = $auth->createPermission('editAndDeleteNote');
        $editAndDeleteNote->description = 'Edit and delete note';
        $auth->add($editAndDeleteNote);


        $authorRule = new AuthorRule();
        $auth->add($authorRule);

        $editAndDeleteOwnNote = $auth->createPermission('editAndDeleteOwnNote');
        $editAndDeleteOwnNote->description = 'Edit and delete own note';
        $editAndDeleteOwnNote->ruleName = $authorRule->name;
        $auth->add($editAndDeleteOwnNote);
        $auth->removeChildren($editAndDeleteOwnNote);
        $auth->addChild($editAndDeleteOwnNote, $editAndDeleteNote);

        $viewOwnNote = $auth->createPermission('viewOwnNote');
        $viewOwnNote->description = 'View own note';
        $viewOwnNote->ruleName = $authorRule->name;
        $auth->add($viewOwnNote);

        $auth->removeChildren($viewOwnNote);
        $auth->addChild($viewOwnNote, $viewNote);

        $student = $auth->getRole('student');
        if (!$student) {
            $student = $auth->createRole('student');
            $auth->add($student);
        }
        $auth->removeChildren($student);
        $auth->addChild($student, $createNote);
        $auth->addChild($student, $viewOwnNote);
        $auth->addChild($student, $editAndDeleteOwnNote);

        $teacher = $auth->getRole('teacher');
        if (!$teacher) {
            $teacher = $auth->createRole('teacher');
            $auth->add($teacher);
        }
        $auth->removeChildren($teacher);
        $auth->addChild($teacher, $viewUser);
        $auth->addChild($teacher, $student);

        $admin = $auth->getRole('admin');
        if (!$admin) {
            $admin = $auth->createRole('admin');
            $auth->add($admin);
        }
        $auth->removeChildren($admin);
        $auth->addChild($admin, $createUser);
        $auth->addChild($admin, $updateUser);
        $auth->addChild($admin, $deleteUser);
        $auth->addChild($admin, $viewNote);
        $auth->addChild($admin, $teacher);
        $auth->addChild($admin, $editAndDeleteNote);

    }
}