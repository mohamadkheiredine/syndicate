<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Aws\SecretsManager\SecretsManagerClient;
use Aws\Exception\AwsException;
use Cache;

class GetSmtp extends Command
{
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'get:smtp';

    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'Get SMTP credentials';

    /**
    * Execute the console command.
    *
    * @param  \App\Support\DripEmailer  $drip
    * @return mixed
    */
    public function handle()
    {
        $client = new SecretsManagerClient([
            'profile' => 'default',
            'version' => '2017-10-17',
            'region' => 'us-east-1',
        ]);

        $secretName = 'ses_secrets';

        try {
            $result = $client->getSecretValue([
                'SecretId' => $secretName,
            ]);

        } catch (AwsException $e) {
            $error = $e->getAwsErrorCode();
            if ($error == 'DecryptionFailureException') {
                // Secrets Manager can't decrypt the protected secret text using the provided AWS KMS key.
                // Handle the exception here, and/or rethrow as needed.
                throw $e;
            }
            if ($error == 'InternalServiceErrorException') {
                // An error occurred on the server side.
                // Handle the exception here, and/or rethrow as needed.
                throw $e;
            }
            if ($error == 'InvalidParameterException') {
                // You provided an invalid value for a parameter.
                // Handle the exception here, and/or rethrow as needed.
                throw $e;
            }
            if ($error == 'InvalidRequestException') {
                // You provided a parameter value that is not valid for the current state of the resource.
                // Handle the exception here, and/or rethrow as needed.
                throw $e;
            }
            if ($error == 'ResourceNotFoundException') {
                // We can't find the resource that you asked for.
                // Handle the exception here, and/or rethrow as needed.
                throw $e;
            }
        }
        // Decrypts secret using the associated KMS CMK.
        // Depending on whether the secret is a string or binary, one of these fields will be populated.
        if (isset($result['SecretString'])) {
            $secret = json_decode($result['SecretString'], true);
        } else {
            $secret = base64_decode($result['SecretBinary']);
        }

        // Cache Secret
        Cache::put('MAIL_DRIVER', $secret['MAIL_DRIVER']);
        Cache::put('MAIL_HOST', $secret['MAIL_HOST']);
        Cache::put('MAIL_PORT', $secret['MAIL_PORT']);
        Cache::put('ACCESS_KEY', $secret['ACCESS_KEY']);
        Cache::put('SECRET_KEY', $secret['SECRET_KEY']);
        Cache::put('MAIL_ENC', $secret['MAIL_ENC']);
        Cache::put('MAIL_FROM', $secret['MAIL_FROM']);
    }

}