<?php

namespace App\Denormalizer;

use App\Entity\Review;
use App\Entity\Reviews;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;


class ReviewDenormalizer implements DenormalizerInterface
{
    use DenormalizerAwareTrait;
    public function __construct(private LoggerInterface $logger) { }
    
    public function supportsDenormalization($data, string $type, string $format = null): bool {
       
        if(($format === 'json') && ($type === "App\Entity\Review[]")){
            $this->logger->debug(
                __METHOD__ . " Type: " . $type . " supported. Returning true with data: " . json_encode($data)
            );
            
            return true;
        }
        
        return false;
    }
    
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $this->logger->debug(
            __METHOD__ . " Denormalising php type: " . gettype($data) . " type: " . $type . " context: " .
            json_encode($context)
        );
        
        $reviews = new Reviews();
        
        foreach($data as $reviewsEl) {
            $review = $this->denormalizer->denormalize($reviewsEl, Review::class, $format, $context);
            $reviews->add($review);
        }
        
        return $reviews;
    }
}