<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class OAIController extends AbstractController 
{

  /**
   * Returns OAI
   *
   * @param Request The current HTTP request
   *
   * @return Response A Response instance
   *
	 * @Route("/oai", name="oai_base")
   */

    public function index(){
        date_default_timezone_set('UTC');

        $base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'];
        $base_identifier = $_SERVER['SERVER_NAME'];

        if(isset($_GET['identifier'])){
            $dataset_uid = explode(':', $_GET['identifier']);
            $dataset_uid = (int)end($dataset_uid);
            $results = $this->getDoctrine()->getRepository('App:Dataset')->findBy(['dataset_uid'=>$dataset_uid]);
        }
        else{
            //$results = $this->getDoctrine()->getRepository('App:Dataset')->findAll();
            
            $repo=$this->getDoctrine()->getRepository('App:Dataset');



						$qb = $repo->createQueryBuilder('d')
					    ->join('d.resource_types', 'r')
					    ->where('r.resource_type = :rti')->setParameter('rti', 'Dataset')
					    ->getQuery();
            
            $results = $qb->getResult();

        }

				//
				// Reduce set if setspec is specified
				
        $set = isset($_GET['set'])?trim($_GET['set']):null;
				
				if ($set !== null && $set !== '' && $set !== '0') {
				
					if ($set==='DNI-Primo') {
                        foreach($results as $r=>$rv) {
							
							$found=0;
							$dl=$rv->getDataLocations();
							foreach($dl as $dv) {
								if (strpos($dv->getDataAccessUrl(), 'd-scholarship.pitt.edu')!==FALSE) {
									$found=1;
									break;
								}
							}

							if ($found==0) {unset($results[$r]);}

						}
                    } elseif ($set==='PrimoIngest') {
                        foreach($results as $r=>$rv) {
							
							$dl=$rv->getDataLocations();
							foreach($dl as $dv) {
								if (strpos($dv->getDataAccessUrl(), 'd-scholarship.pitt.edu')!==FALSE) {
									unset($results[$r]);
									break;
								}
							}

						}
                    }

				}

        $template = '';
        $verb = isset($_GET['verb'])?trim($_GET['verb']):'Identify';
        switch($verb){
            case 'Identify':
                $template = 'oai_identify.xml.twig';
                break;
            case 'ListRecords':
            case 'GetRecord':
                $template = 'oai_list_records.xml.twig';
                break;
            case 'ListSets':
                $template = 'oai_list_sets.xml.twig';
                break;
            case 'ListMetadataFormats':
                    $template = 'oai_list_metadata_formats.xml.twig';
                    break;
            case 'ListIdentifiers':
                $template = 'oai_list_identifiers.xml.twig';
                break;
        }

        $response = new Response(
            $this->renderView('oai_base.xml.twig', array(
                'oai_template' => $template,
                'timestamp' => date('Y-m-d\TH:i:s\Z'),
                'base_url' => $base_url,
                'base_identifier' => $base_identifier,
                'publisher' => '',
                'results' => $results
            ))
        );
        $response->headers->set('Content-Type', 'text/xml');
        return $response;
    }
}
