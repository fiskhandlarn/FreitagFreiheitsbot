<?php

/**
 * Wochenende command
 */

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class WochenendeCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'wochenende';

    /**
     * @var string
     */
    protected $description = 'Wochenende command';

    /**
     * @var string
     */
    protected $usage = '/wochenende';

    /**
     * @var string
     */
    protected $version = '1.2.0';

    /**
     * @var bool
     */
    protected $private_only = false;

    /**
     * Main command execution
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {
        date_default_timezone_set('Europe/Stockholm');

        $weekday = intval(date('w'));

        if (
            (5 === $weekday && time() >= $this->getTimestampForWeek(intval(date('W'))))
            || 6 === $weekday
            || 0 === $weekday
        ) {
            if (
                $message = $this->getMessage() ?:
                $this->getEditedMessage() ?:
                $this->getChannelPost() ?:
                $this->getEditedChannelPost()
            ) {
                return Request::sendAnimation([
                    'chat_id' => ($message->getChat() ?: $message->getFrom())->getId(),
                    'caption' => 'Ja, saufen!',
                    'animation'   => 'https://c.tenor.com/8QtN1_MFXaIAAAAC/wochenende-saufen.gif',
                ]);
            } else {
                return $this->replyToChat(
                    'Fail, pls contact admin.'
                );
            }
        } else {
            return $this->replyToChat(
                'Nein.'
            );
        }
    }

    private function getTimestampForWeek($week): int
    {
        $startTime = strtotime('Friday 12:00:00');
        $endTime = strtotime('Friday 17:00:00');
        $nrWeeks = (new \DateTime('December 28th'))->format('W'); // https://stackoverflow.com/a/21480444

        $allOffets = [];
        for ($i = 1; $i <= $nrWeeks; $i++) {
            $allOffets [] = hexdec(crc32($i)); // https://stackoverflow.com/a/32100605
        }

        $allOffsetsSorted = $allOffets;
        sort($allOffsetsSorted);

        $firstOffset = $allOffsetsSorted[0];
        $lastOffset = $allOffsetsSorted[$nrWeeks - 1];

        return
            intval(
                ($allOffets[$week - 1] - $firstOffset)
                / ($lastOffset - $firstOffset)
                * ($endTime - $startTime)
            ) + $startTime;
    }
}
