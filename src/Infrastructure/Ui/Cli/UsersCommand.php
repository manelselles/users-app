<?php

namespace UsersApp\Infrastructure\Ui\Cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use UsersApp\Application\CreateUser;
use UsersApp\Domain\UserRepository;

class UsersCommand extends Command
{
    const LIST_USERS = 'List users';
    const CREATE_USER = 'Create user';
    /**
     * @var CreateUser
     */
    private $createUser;
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @param CreateUser $createUser
     * @param UserRepository $userRepository
     */
    public function __construct(CreateUser $createUser, UserRepository $userRepository)
    {
        parent::__construct();
        $this->createUser = $createUser;
        $this->userRepository = $userRepository;
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName('ulabox:users')->setDescription('Manage users from the command line');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $action = $this->askForAction($input, $output, $helper);
        $output->writeln(sprintf('<comment>You have just selected: %s </comment>', $action));

        if ($action === self::LIST_USERS) {
            $this->listUsers($output);
        } elseif ($action === self::CREATE_USER) {
            $this->createUser($input, $output, $helper);
        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param QuestionHelper $helper
     * @return array
     */
    protected function askForAction(InputInterface $input, OutputInterface $output, QuestionHelper $helper)
    {
        $question = new ChoiceQuestion(
            '<question>Please select the action</question>',
            [self::LIST_USERS, self::CREATE_USER]
        );
        $question->setErrorMessage('Option %s is invalid.');
        $action = $helper->ask($input, $output, $question);

        return $action;
    }

    /**
     * @param OutputInterface $output
     */
    protected function listUsers(OutputInterface $output)
    {
        $users = $this->userRepository->all();
        foreach ($users as $user) {
            $output->writeln(
                sprintf('%s -- %s -- %s', $user->username(), $user->password(), implode(',', $user->roles()))
            );
        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param QuestionHelper $helper
     */
    protected function createUser(InputInterface $input, OutputInterface $output, QuestionHelper $helper)
    {
        $question = new Question('<question>Please enter the name of the user:</question>' . PHP_EOL);
        $username = $helper->ask($input, $output, $question);

        $question = new Question('<question>Please enter the password of the user:</question>' . PHP_EOL);
        $question->setHidden(true);
        $question->setHiddenFallback(false);
        $userPassword = $helper->ask($input, $output, $question);

        try {
            $this->createUser->create($username, $userPassword);
            $output->writeln(sprintf('<info>Created user with username %s</info>', $username));
        } catch (\Exception $e) {
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
        }
    }
}