<?php
namespace App\Services;
use Twilio\Rest\Client;

class EnvoyerMessage
{
    private static ?\App\Services\EnvoyerMessage $instance = null;
    private string $accountSid;
    private string $authToken;
    private string $twilioNumber;
    
    private function __construct()
    {
        $this->accountSid = defined('TWILIO_SID') ?? TWILIO_SID;
        $this->authToken = defined('TWILIO_TOKEN') ?? TWILIO_TOKEN ;
        $this->twilioNumber = defined('TWILIO_FROM') ?? TWILIO_FROM ;
    }
    
    public static function getInstance(): \App\Services\EnvoyerMessage
    {
        if (self::$instance === null) {
            self::$instance = new \App\Services\EnvoyerMessage();
        }
        return self::$instance;
    }
    

    private static function envoyerSms(string $destinataire, string $message): array
    {
        $instance = self::getInstance();
        
        try {
            if (empty($instance->accountSid) || empty($instance->authToken) || empty($instance->twilioNumber)) {
                throw new \Exception("Configuration Twilio incomplète. Vérifiez vos variables d'environnement.");
            }
            
            if (!str_starts_with($destinataire, '+')) {
                $destinataire = '+221' . ltrim($destinataire, '0');
            }
            
            $client = new Client($instance->accountSid, $instance->authToken);
            
            $sms = $client->messages->create(
                $destinataire,
                [
                    'from' => $instance->twilioNumber,
                    'body' => $message
                ]
            );
            
            return [
                'success' => true,
                'sid' => $sms->sid,
                'status' => $sms->status
            ];
        } catch (\Exception $e) {
            error_log('Erreur lors de l\'envoi du SMS: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    


    public static function envoyerConfirmationInscription(string $numeroTelephone, string $nom, string $prenom): array
    {
        $message = "Bonjour $prenom $nom, votre inscription à MAXIT a été effectuée avec succès. Bienvenue !";
        return self::envoyerSms($numeroTelephone, $message);
    }
}