<?php
/**
 * Command to ease the creation of an OAuth Client
 *
 * @package   package-of-laravel/oauth2-server-laravel
 * @author    Pralhad Kumar Shrestha <pralhad.shrestha05@gmail.com>
 * @copyright Copyright (c) Pralhad Kumar Shrestha
 * @licence   http://mit-license.org/
 * @link      https://github.com/package-of-laravel/oauth2-server-laravel
 */

namespace LucaDegasperi\OAuth2Server\Console;

use Illuminate\Console\Command;
use LucaDegasperi\OAuth2Server\Storage\FluentRefreshToken;
use LucaDegasperi\OAuth2Server\Storage\FluentAccessToken;
use LucaDegasperi\OAuth2Server\Storage\FluentSession;
use Illuminate\Database\ConnectionResolverInterface as Resolver;

class ClearExpiredTokensCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'oauth2-server:clear-expired-tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove expired refresh tokens, access tokens, and sessions';

    /**
     * @var Resolver
     */
    protected $clientRepo;

    public function __construct()
    {
        parent::__construct();
        $this->clientRepo = app('db');
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        // Remove expired refresh tokens
        $refreshTokenRepo = new FluentRefreshToken($this->clientRepo);
        $refreshTokenRepo->deleteExpired();

        // Remove expired access tokens
        $accessTokenRepo = new FluentAccessToken($this->clientRepo);
        $accessTokenRepo->deleteExpired();

        // Remove expired sessions
        $sessionRepo = new FluentSession($this->clientRepo);
        $sessionRepo->deleteExpired();

        $this->info('Expired tokens and sessions have been cleared.');
    }
}
