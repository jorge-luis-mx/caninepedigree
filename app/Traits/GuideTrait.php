<?php

namespace App\Traits;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;



trait GuideTrait {


    public function getTutorials(){


        
        $data = [

            'instructions'=>[
                'You have registered at least 1 airport',
                'You have created and associated a map to your airport',
                'Have selected the type of service you provide',
                'Enter the rates for your services'
            ],
            'stepTutorials'=>[

                [
                    'id'=>1,
                    'title'=>"Register an Airport",
                    'description'=>[
                        "Registering an Airport will allow you to configure your service area, because our travelers are looking for transportation options from and Airport to a point in the city or surroundings, the first and most important step is to determine from and to which Airport you can provide your transportation services",

                    ],
                    'tag'=>"Let's register an Airport",
                    'url'=>"airport",
                    'video'=>""
                ],
                [
                    'id'=>2,
                    'title'=>"Create a Map",
                    'description'=>[
                        "Once you have registered an airport, it is time to create a map with the zones,areas where your transport services have converage.",
                        "This step is critical, so that later you can enter the rates of your services from your registered Airport to your coverage zones."
                    ],
                    'tag'=>"Let's create a Map",
                    'url'=>"map",
                    'video'=>""
                ],
                [
                    'id'=>3,
                    'title'=>"Choose your services type",
                    'description'=>[
                        "Now it is time to choose the services(s)  you are able to provide according to the characteristic of the vehicles you have.",
                        "The types of services listed can only be selected by you, do not worry if the model of your vehicle does not match the ilustration on the platform, the important thing is that it meets the requirements of the category, such as capacity."
                    ],
                    'tag'=>"Let's choose your services",
                    'url'=>"service/type",
                    'video'=>""
                ],
                [
                    'id'=>4,
                    'title'=>"Enter your Rates",
                    'description'=>[
                        "We are now at the last step.",
                        "It's time to assing the rates of your services si that the travelers of our platform can find you in our Web Site as a transfer option from the Airport you have configured to one of your zones created throung your map."
                    ],
                    'tag'=>"Let's enter your rates",
                    'url'=>"pricing",
                    'video'=>""
                ]
            ]
            
        ];

        return $data;

    }


}