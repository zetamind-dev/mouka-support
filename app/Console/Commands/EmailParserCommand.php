<?php

#!/usr/bin/php
namespace ComplainDesk\Console\Commands;

use Illuminate\Console\Command;
use ZBateson\MailMimeParser\MailMimeParser;
use ZBateson\MailMimeParser\Message;
use GuzzleHttp\Psr7;
use ComplainDesk\Email;

class EmailParserCommand extends Command
{
/**
 * The name and signature of the console command.
 *
 * @var string
 */
protected $signature = 'email:parse';

/**
 * The console command description.
 *
 * @var string
 */
protected $description = 'Parse an incoming email.';

/**
 * Create a new command instance.
 *
 * @return void
 */
public function __construct()
{
    parent::__construct();
}

/**
 * Execute the console command.
 *
 * @return mixed
 */
public function handle()
{
        // read from stdin
    $fd = fopen("php://stdin", "r");
    $rawEmail = "";
    while (!feof($fd)) {
        $rawEmail .= fread($fd, 1024);
    }
    fclose($fd);
    
    // Create a new instance of MailMimeParser
    $message = Message::parse($handleOrStreamOrString);
    $subject = $message->getHeaderValue('Subject');
    $text = $message->getTextContent();
    $from = $message->getHeader('From');
    $to = $message->getHeader('To');
    $cc = $message->getHeader('Cc');
    $fromName = $from->getName();

    // Save email contents to database
    // Create a new instance of the Email model

    $email = new Email;
    $email->subject = $subject;
    $email->text = $text;
    $email->from = $from;
    $email->to = $to;
    $email->cc = $cc;
    $email->fromName = $fromName;
   
    $email->save();
}
}
