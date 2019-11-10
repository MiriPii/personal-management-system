<?php
/**
 * Created by PhpStorm.
 * User: volmarg
 * Date: 29.05.19
 * Time: 21:02
 */

namespace App\Controller\Utils;


use App\Entity\Modules\Schedules\MySchedule;
use App\Repository\FilesSearchRepository;
use App\Repository\FilesTagsRepository;
use App\Repository\Modules\Achievements\AchievementRepository;
use App\Repository\Modules\Contacts\MyContactsGroupsRepository;
use App\Repository\Modules\Contacts\MyContactsRepository;
use App\Repository\Modules\Contacts2\MyContactRepository;
use App\Repository\Modules\Contacts2\MyContactTypeRepository;
use App\Repository\Modules\Goals\MyGoalsPaymentsRepository;
use App\Repository\Modules\Goals\MyGoalsRepository;
use App\Repository\Modules\Goals\MyGoalsSubgoalsRepository;
use App\Repository\Modules\Job\MyJobAfterhoursRepository;
use App\Repository\Modules\Job\MyJobHolidaysPoolRepository;
use App\Repository\Modules\Job\MyJobHolidaysRepository;
use App\Repository\Modules\Job\MyJobSettingsRepository;
use App\Repository\Modules\Notes\MyNotesRepository;
use App\Repository\Modules\Notes\MyNotesCategoriesRepository;
use App\Repository\Modules\Passwords\MyPasswordsGroupsRepository;
use App\Repository\Modules\Passwords\MyPasswordsRepository;
use App\Repository\Modules\Payments\MyPaymentsBillsItemsRepository;
use App\Repository\Modules\Payments\MyPaymentsBillsRepository;
use App\Repository\Modules\Payments\MyPaymentsMonthlyRepository;
use App\Repository\Modules\Payments\MyPaymentsOwedRepository;
use App\Repository\Modules\Payments\MyPaymentsProductRepository;
use App\Repository\Modules\Payments\MyPaymentsSettingsRepository;
use App\Repository\Modules\Payments\MyRecurringPaymentMonthlyRepository;
use App\Repository\Modules\Reports\ReportsRepository;
use App\Repository\Modules\Schedules\MyScheduleRepository;
use App\Repository\Modules\Schedules\MyScheduleTypeRepository;
use App\Repository\Modules\Shopping\MyShoppingPlansRepository;
use App\Repository\Modules\Travels\MyTravelsIdeasRepository;
use App\Repository\SettingRepository;
use App\Repository\UserRepository;
use App\Services\Exceptions\ExceptionDuplicatedTranslationKey;
use App\Services\Exceptions\ExceptionRepository;
use App\Services\Translator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class Repositories extends AbstractController {

    const ACHIEVEMENT_REPOSITORY_NAME                   = 'AchievementRepository';
    const MY_NOTES_REPOSITORY_NAME                      = 'MyNotesRepository';
    const MY_NOTES_CATEGORIES_REPOSITORY_NAME           = 'MyNotesCategoriesRepository';
    const MY_JOB_AFTERHOURS_REPOSITORY_NAME             = 'MyJobAfterhoursRepository';
    const MY_PAYMENTS_MONTHLY_REPOSITORY_NAME           = 'MyPaymentsMonthlyRepository';
    const MY_PAYMENTS_PRODUCTS_REPOSITORY_NAME          = 'MyPaymentsProductRepository';
    const MY_PAYMENTS_SETTINGS_REPOSITORY_NAME          = 'MyPaymentsSettingsRepository';
    const MY_SHOPPING_PLANS_REPOSITORY_NAME             = 'MyShoppingPlansRepository';
    const MY_TRAVELS_IDEAS_REPOSITORY_NAME              = 'MyTravelsIdeasRepository';
    const INTEGRATIONS_RESOURCES_REPOSITORY_NAME        = 'IntegrationResourceRepository';
    const MY_CONTACTS_REPOOSITORY_NAME                  = 'MyContactsRepository';
    const MY_CONTACTS_GROUPS_REPOSITORY_NAME            = 'MyContactsGroupsRepository';
    const MY_PASSWORDS_REPOSITORY_NAME                  = 'MyPasswordsRepository';
    const MY_PASSWORDS_GROUPS_REPOSITORY_NAME           = 'MyPasswordsGroupsRepository';
    const USER_REPOSITORY                               = 'UserRepository';
    const MY_GOALS_REPOSITORY_NAME                      = 'MyGoalsRepository';
    const MY_SUBGOALS_REPOSITORY_NAME                   = 'MyGoalsSubgoalsRepository';
    const MY_GOALS_PAYMENTS_REPOSITORY_NAME             = 'MyGoalsPaymentsRepository';
    const MY_JOB_HOLIDAYS_REPOSITORY_NAME               = 'MyJobHolidaysRepository';
    const MY_JOB_HOLIDAYS_POOL_REPOSITORY_NAME          = 'MyJobHolidaysPoolRepository';
    const MY_JOB_SETTINGS_REPOSITORY_NAME               = 'MyJobSettingsRepository';
    const MY_PAYMENTS_OWED_REPOSITORY_NAME              = 'MyPaymentsOwedRepository';
    const MY_PAYMENTS_BILLS_REPOSITORY_NAME             = 'MyPaymentsBillsRepository';
    const MY_PAYMENTS_BILLS_ITEMS_REPOSITORY_NAME       = 'MyPaymentsBillsItemsRepository';
    const FILE_TAGS_REPOSITORY                          = 'FilesTagsRepository';
    const REPORTS_REPOSITORY                            = 'ReportsRepository';
    const MY_RECURRING_PAYMENT_MONTHLY_REPOSITORY_NAME  = 'MyRecurringPaymentMonthlyRepository';
    const SETTING_REPOSITORY                            = 'SettingRepository';
    const MY_SCHEDULE_REPOSITORY                        = "MyScheduleRepository";
    const MY_SCHEDULE_TYPE_REPOSITORY                   = "MyScheduleTypeRepository";
    const MY_CONTACT_REPOSITORY                         = "MyContactRepository";
    const MY_CONTACT_TYPE_REPOSITORY                    = "MyContactTypeRepository";

    const PASSWORD_FIELD                            = 'password';

    /**
     * @var Translator $translator
     */
    private $translator;

    /**
     * @var MyNotesRepository $myNotesRepository
     */
    public $myNotesRepository;

    /**
     * @var AchievementRepository
     */
    public $achievementRepository;

    /**
     * @var MyJobAfterhoursRepository
     */
    public $myJobAfterhoursRepository;

    /**
     * @var MyPaymentsMonthlyRepository
     */
    public $myPaymentsMonthlyRepository;

    /**
     * @var MyPaymentsProductRepository
     */
    public $myPaymentsProductRepository;

    /**
     * @var MyShoppingPlansRepository
     */
    public $myShoppingPlansRepository;

    /**
     * @var MyTravelsIdeasRepository
     */
    public $myTravelsIdeasRepository;

    /**
     * @var MyPaymentsSettingsRepository
     */
    public $myPaymentsSettingsRepository;

    /**
     * @var MyNotesCategoriesRepository
     */
    public $myNotesCategoriesRepository;

    /**
     * @var MyContactsRepository
     */
    public $myContactsRepository;

    /**
     * @var MyContactsGroupsRepository
     */
    public $myContactsGroupsRepository;

    /**
     * @var MyPasswordsRepository
     */
    public $myPasswordsRepository;

    /**
     * @var MyPasswordsGroupsRepository
     */
    public $myPasswordsGroupsRepository;

    /**
     * @var UserRepository
     */
    public $userRepository;

    /**
     * @var MyGoalsRepository
     */
    public $myGoalsRepository;

    /**
     * @var MyGoalsSubgoalsRepository
     */
    public $myGoalsSubgoalsRepository;

    /**
     * @var MyGoalsPaymentsRepository
     */
    public $myGoalsPaymentsRepository;

    /**
     * @var MyJobHolidaysRepository
     */
    public $myJobHolidaysRepository;

    /**
     * @var MyJobHolidaysPoolRepository
     */
    public $myJobHolidaysPoolRepository;

    /**
     * @var MyJobSettingsRepository
     */
    public $myJobSettingsRepository;

    /**
     * @var MyPaymentsOwedRepository
     */
    public $myPaymentsOwedRepository;

    /**
     * @var FilesTagsRepository
     */
    public $filesTagsRepository;

    /**
     * @var FilesSearchRepository $filesSearchRepository
     */
    public $filesSearchRepository;

    /**
     * @var MyPaymentsBillsRepository $myPaymentsBillsRepository
     */
    public $myPaymentsBillsRepository;

    /**
     * @var MyPaymentsBillsItemsRepository $myPaymentsBillsItemsRepository
     */
    public $myPaymentsBillsItemsRepository;

    /**
     * @var ReportsRepository $reportsRepository
     */
    public $reportsRepository;

    /**
     * @var MyRecurringPaymentMonthlyRepository
     */
    public $myRecurringPaymentMonthlyRepository;

    /**
     * @var SettingRepository
     */
    public $settingRepository;

    /**
     * @var MyScheduleRepository $myScheduleRepository
     */
    public $myScheduleRepository;

    /**
     * @var MyScheduleTypeRepository $myScheduleTypeRepository
     */
    public $myScheduleTypeRepository;

    /**
     * @var MyContactRepository $myContactRepository
     */
    public $myContactRepository;

    /**
     * @var MyContactTypeRepository $myContactTypeRepository
     */
    public $myContactTypeRepository;

    public function __construct(
        MyNotesRepository                   $myNotesRepository,
        AchievementRepository               $myAchievementsRepository,
        MyJobAfterhoursRepository           $myJobAfterhoursRepository,
        MyPaymentsMonthlyRepository         $myPaymentsMonthlyRepository,
        MyPaymentsProductRepository         $myPaymentsProductRepository,
        MyShoppingPlansRepository           $myShoppingPlansRepository,
        MyTravelsIdeasRepository            $myTravelIdeasRepository,
        MyPaymentsSettingsRepository        $myPaymentsSettingsRepository,
        MyNotesCategoriesRepository         $myNotesCategoriesRepository,
        MyContactsRepository                $myContactsRepository,
        MyContactsGroupsRepository          $myContactsGroupsRepository,
        MyPasswordsRepository               $myPasswordsRepository,
        MyPasswordsGroupsRepository         $myPasswordsGroupsRepository,
        UserRepository                      $userRepository,
        MyGoalsRepository                   $myGoalsRepository,
        MyGoalsSubgoalsRepository           $myGoalsSubgoalsRepository,
        MyGoalsPaymentsRepository           $myGoalsPaymentsRepository,
        MyJobHolidaysRepository             $myJobHolidaysRepository,
        MyJobHolidaysPoolRepository         $myJobHolidaysPoolRepository,
        MyJobSettingsRepository             $myJobSettingsRepository,
        MyPaymentsOwedRepository            $myPaymentsOwedRepository,
        FilesTagsRepository                 $filesTagsRepository,
        FilesSearchRepository               $filesSearchRepository,
        Translator                          $translator,
        MyPaymentsBillsRepository           $myPaymentsBillsRepository,
        MyPaymentsBillsItemsRepository      $myPaymentsBillsItemsRepository,
        ReportsRepository                   $reportsRepository,
        MyRecurringPaymentMonthlyRepository $myRecurringMonthlyPaymentRepository,
        SettingRepository                   $settingRepository,
        MyScheduleRepository                $myScheduleRepository,
        MyScheduleTypeRepository            $myScheduleTypeRepository,
        MyContactTypeRepository             $myContactTypeRepository,
        MyContactRepository                 $myContactRepository
    ) {
        $this->myNotesRepository                    = $myNotesRepository;
        $this->achievementRepository                = $myAchievementsRepository;
        $this->myJobAfterhoursRepository            = $myJobAfterhoursRepository;
        $this->myPaymentsMonthlyRepository          = $myPaymentsMonthlyRepository;
        $this->myPaymentsProductRepository          = $myPaymentsProductRepository;
        $this->myShoppingPlansRepository            = $myShoppingPlansRepository;
        $this->myTravelsIdeasRepository             = $myTravelIdeasRepository;
        $this->myPaymentsSettingsRepository         = $myPaymentsSettingsRepository;
        $this->myNotesCategoriesRepository          = $myNotesCategoriesRepository;
        $this->myContactsRepository                 = $myContactsRepository;
        $this->myContactsGroupsRepository           = $myContactsGroupsRepository;
        $this->myPasswordsRepository                = $myPasswordsRepository;
        $this->myPasswordsGroupsRepository          = $myPasswordsGroupsRepository;
        $this->userRepository                       = $userRepository;
        $this->myGoalsRepository                    = $myGoalsRepository;
        $this->myGoalsSubgoalsRepository            = $myGoalsSubgoalsRepository;
        $this->myGoalsPaymentsRepository            = $myGoalsPaymentsRepository;
        $this->myJobHolidaysRepository              = $myJobHolidaysRepository;
        $this->myJobHolidaysPoolRepository          = $myJobHolidaysPoolRepository;
        $this->myJobSettingsRepository              = $myJobSettingsRepository;
        $this->myPaymentsOwedRepository             = $myPaymentsOwedRepository;
        $this->filesTagsRepository                  = $filesTagsRepository;
        $this->filesSearchRepository                = $filesSearchRepository;
        $this->translator                           = $translator;
        $this->myPaymentsBillsRepository            = $myPaymentsBillsRepository;
        $this->myPaymentsBillsItemsRepository       = $myPaymentsBillsItemsRepository;
        $this->reportsRepository                    = $reportsRepository;
        $this->myRecurringPaymentMonthlyRepository  = $myRecurringMonthlyPaymentRepository;
        $this->settingRepository                    = $settingRepository;
        $this->myScheduleRepository                 = $myScheduleRepository;
        $this->myScheduleTypeRepository             = $myScheduleTypeRepository;
        $this->myContactTypeRepository              = $myContactTypeRepository;
        $this->myContactRepository                  = $myContactRepository;
    }

    /**
     * @param string $repository_name
     * @param $id
     * This is general method for all common record soft delete called from front
     * @param array $findByParams
     * @return JsonResponse
     * @throws \Exception
     */
    public function deleteById(string $repository_name, $id, array $findByParams = []) {
        try {

            $id         = $this->trimAndCheckId($id);
            $repository = $this->{lcfirst($repository_name)};
            $record     = $repository->find($id);

            if ($this->hasChildren($record, $repository)) {
                $message = $this->translator->translate('exceptions.repositories.recordHasChildrenCannotRemove');
                throw new \Exception($message);
            }

            $record->setDeleted(1);

            $em = $this->getDoctrine()->getManager();

            $em->persist($record);
            $em->flush();

            $message = $this->translator->translate('responses.repositories.recordDeletedSuccessfully');
            return new JsonResponse($message, 200);
        } catch (\Exception | ExceptionRepository $er) {
            $message = $this->translator->translate('responses.repositories.couldNotDeleteRecord');
            return new JsonResponse($message, 500);
        }
    }

    /**
     * @param array $parameters
     * @param $entity
     * This is general method for all common record update called from front
     * @param array $findByParams
     * @return JsonResponse
     *
     * It's required that field for which You want to get entity has this example format in js ajax request:
     * 'category': {
     * "type": "entity",
     * 'namespace': 'App\\Entity\\MyNotesCategories',
     * 'id': $(noteCategoryId).val(),
     * },
     * @throws ExceptionDuplicatedTranslationKey
     */
    public function update(array $parameters, $entity, array $findByParams = []) {

        try {
            unset($parameters['id']);

            foreach ($parameters as $parameter => $value) {

                /**
                 * The only situation where this will be array is entity type parameter - does not need to be trimmed
                 */
                if(!is_array($value)){
                    $value = trim($value);
                }

                if ($value === "true") {
                    $value = true;
                }
                if ($value === "false") {
                    $value = false;
                }

                // Info/Todo: the password check should not be here...
                if ($parameter === static::PASSWORD_FIELD && !$this->isPasswordValueValid($value)) {
                    $message = $this->translator->translate('responses.password.changeHasBeenCanceled');
                    return new JsonResponse($message, 500);
                }

                if (is_array($value)) {
                    if (array_key_exists('type', $value) && $value['type'] == 'entity') {
                        $value = $this->getEntity($value);
                    }
                }

                $methodName = 'set' . ucfirst($parameter);
                $methodName = (strstr($methodName, '_id') ? str_replace('_id', 'Id', $methodName) : $methodName);

                if (is_object($value)) {
                    $entity->$methodName($value);
                    continue;
                }
                $entity->$methodName($value);

            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $message = $this->translator->translate('responses.repositories.recordUpdateSuccess');
            return new JsonResponse($message, 200);
        } catch (ExceptionRepository $er) {
            $message = $this->translator->translate('responses.repositories.recordUpdateFail');
            return new JsonResponse($message, 500);
        }
    }

    private function getEntity(array $entity_data) {
        $entity = null;

        try {

            if (array_key_exists('namespace', $entity_data) && array_key_exists('id', $entity_data)) {
                $entity = $this->getDoctrine()->getRepository($entity_data['namespace'])->find($entity_data['id']);
            }

        } catch (ExceptionRepository $er) {
            echo $er->getMessage();
        }

        return $entity;
    }

    private function hasChildren($record, $repository) {
        $parent_keys = ['parent', 'parent_id', 'parentId', 'parentID'];
        $result = false;

        foreach ($parent_keys as $key) {

            if (property_exists($record, $key)) {
                $child_record = $repository->findBy([$key => $record->getId(), 'deleted' => 0]);
            }

            if (isset($child_record) && !empty($child_record)) {
                $result = true;
                break;
            }

        }

        return $result;
    }

    public static function removeHelperColumnsFromView(array &$columns_names) {
        $columns_to_remove = ['deleted', 'delete'];

        foreach ($columns_to_remove as $column_to_remove) {
            $key = array_search($column_to_remove, $columns_names);

            if (!is_null($key) && $key) {
                unset($columns_names[$key]);
            }

        }
    }

    private function isPasswordValueValid($value) {
        return !empty($value);
    }

    /**
     * This function trims id and rechecks if it's int
     * The problem is that js keeps getting all the whitespaces and new lines in to many places....
     *
     * @param $id
     * @return string
     * @throws \Exception
     */
    private function trimAndCheckId($id){
        $id = (int) trim($id);

        if (!is_numeric($id)) {
            $message = $this->translator->translate('responses.repositories.inorrectId') . $id;
            throw new \Exception($message);
        }

        return $id;
    }
}