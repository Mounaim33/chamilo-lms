<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\PluginBundle\MigrationMoodle\Loader;

use Chamilo\PluginBundle\MigrationMoodle\Interfaces\LoaderInterface;

/**
 * Class LessonQuestionPagesQuizLoader.
 *
 * Loader for create a quiz according the transformed data coming from a moodle's lesson question page.
 *
 * @package Chamilo\PluginBundle\MigrationMoodle\Loader
 */
class LessonQuestionPagesQuizLoader implements LoaderInterface
{
    /**
     * Load the data and return the ID inserted.
     *
     * @param array $incomingData
     *
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Exception
     *
     * @return int
     */
    public function load(array $incomingData)
    {
        $exercise = new \Exercise($incomingData['c_id']);
        $exercise->updateTitle(\Exercise::format_title_variable($incomingData['item_title']));
        $exercise->updateDescription('');
        $exercise->updateAttempts(0);
        $exercise->updateFeedbackType(0);
        $exercise->updateType(ALL_ON_ONE_PAGE);
        $exercise->setRandom(0);
        $exercise->updateRandomAnswers(0);
        $exercise->updateResultsDisabled(0);
        $exercise->updateExpiredTime(0);
        $exercise->updateTextWhenFinished('');
        $exercise->updateDisplayCategoryName(1);
        $exercise->updatePassPercentage(0);
        $exercise->setQuestionSelectionType(1);
        $exercise->setHideQuestionTitle(0);
        $exercise->sessionId = 0;
        $exercise->start_time = null;
        $exercise->end_time = null;

        $quizId = $exercise->save();

        \Database::getManager()
            ->createQuery('UPDATE ChamiloCourseBundle:CLpItem i SET i.path = :path WHERE i.iid = :id')
            ->setParameters(['path' => $quizId, 'id' => $incomingData['item_id']])
            ->execute();

        return $quizId;
    }
}
