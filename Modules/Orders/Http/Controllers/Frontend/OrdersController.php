<?php

namespace Modules\Orders\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Contacts\Models\Contact;
use Modules\SocialMedia\Models\SocialMedia;
use Modules\GeneralSettings\Models\GeneralSetting;

class OrdersController extends Controller
{
    public function complete_order()
    {
        $data['title'] = 'Complete Order';
        $data['header'] = $this->getHeader();
        $data['footer'] = $this->getFooter();
        $data["filter"] = $this->dataSideFilter();
        $data['url_active'] = "completeorder";

        $data['contact'] = Contact::first();
        $data['social_media'] = SocialMedia::all();
        $data['general_setting'] = GeneralSetting::first(); 

        return view('frontend/pages/completeorder', $data);
    }

    private function getHeader(){
        $data['img_company'] = "logo-company.png";
        $data['data_item_kitchen'] = [
                                        "title_category"=>"kitchen",
                                        "item"=>[
                                                    [
                                                        "product_name_eng"=>"honey rambutan",
                                                        "product_name_ind"=>"madu rambutan",
                                                        "product_img"=>"backdrop_menu.jpg",
                                                        "product_img_mobile"=>"TJ700663.png"
                                                    ],
                                                    [
                                                        "product_name_eng"=>"coconut milk powder 40% fat",
                                                        "product_name_ind"=>"santan bubuk lemak 40%",  
                                                        "product_img"=>"",
                                                        "product_img_mobile"=>"TJ70066301.png"                                                        
                                                    ],
                                                    [
                                                        "product_name_eng"=>"coriander powder",
                                                        "product_name_ind"=>"ketumbar bubuk",   
                                                        "product_img"=>"coriander-powder.jpg",
                                                        "product_img_mobile"=>"TJ70066302.png"
                                                    ],
                                                    [
                                                        "product_name_eng"=>"honey hevea",
                                                        "product_name_ind"=>"madu karet",   
                                                        "product_img"=>"",
                                                        "product_img_mobile"=>"TJ70066303.png"
                                                    ],
                                                    [
                                                        "product_name_eng"=>"nutmeg powder",
                                                        "product_name_ind"=>"pala bubuk",   
                                                        "product_img"=>"",
                                                        "product_img_mobile"=>"TJ70066304.png" 
                                                    ],
                                                    [
                                                        "product_name_eng"=>"coriander whole",
                                                        "product_name_ind"=>"ketumbar utuh",  
                                                        "product_img"=>"",
                                                        "product_img_mobile"=>"TJ70066305.png"  
                                                    ],
                                                    [
                                                        "product_name_eng"=>"honey randu",
                                                        "product_name_ind"=>"madu randu", 
                                                        "product_img"=>"",
                                                        "product_img_mobile"=>"TJ70066306.png"   
                                                    ],
                                                    [
                                                        "product_name_eng"=>"white pepper whole",
                                                        "product_name_ind"=>"lada putih utuh",  
                                                        "product_img"=>"",
                                                        "product_img_mobile"=>"TJ70066307.png"  
                                                    ],
                                                    [
                                                        "product_name_eng"=>"chilli powder",
                                                        "product_name_ind"=>"cabe bubuk",   
                                                        "product_img"=>"",
                                                        "product_img_mobile"=>"TJ70066308.png" 
                                                    ],
                                                    [
                                                        "product_name_eng"=>"coconut sugar organic",
                                                        "product_name_ind"=>"gula kelapa organik",
                                                        "product_img"=>"",
                                                        "product_img_mobile"=>"TJ70066309.png"    
                                                    ],
                                                    [
                                                        "product_name_eng"=>"white pepper powder",
                                                        "product_name_ind"=>"lada putih bubuk",   
                                                        "product_img"=>"",
                                                        "product_img_mobile"=>"TJ70066310.png" 
                                                    ],
                                                    [
                                                        "product_name_eng"=>"clove bud",
                                                        "product_name_ind"=>"cengkeh", 
                                                        "product_img"=>"",
                                                        "product_img_mobile"=>"TJ70066311.png"    
                                                    ],
                                                    [
                                                        "product_name_eng"=>"coconut sugar",
                                                        "product_name_ind"=>"gula kelapa", 
                                                        "product_img"=>"",
                                                        "product_img_mobile"=>"TJ70066313.png"    
                                                    ],
                                                    [
                                                        "product_name_eng"=>"black pepper powder",
                                                        "product_name_ind"=>"lada hitam bubuk", 
                                                        "product_img"=>"",
                                                        "product_img_mobile"=>"TJ70066314.png"    
                                                    ],
                                                    [
                                                        "product_name_eng"=>"cumin",
                                                        "product_name_ind"=>"jintan putih",  
                                                        "product_img"=>"",
                                                        "product_img_mobile"=>"TJ70066315.png"   
                                                    ],
                                                    [
                                                        "product_name_eng"=>"coconut milk powder 60% fat",
                                                        "product_name_ind"=>"santan bubuk lemak 60%",   
                                                        "product_img"=>"",
                                                        "product_img_mobile"=>"TJ70066316.png"  
                                                    ],
                                                    [
                                                        "product_name_eng"=>"black pepper whole",
                                                        "product_name_ind"=>"lada hitam utuh",   
                                                        "product_img"=>"",
                                                        "product_img_mobile"=>"TJ70066317.png"  
                                                    ],
                                                    [
                                                        "product_name_eng"=>"fennel seed",
                                                        "product_name_ind"=>"adas",   
                                                        "product_img"=>"",
                                                        "product_img_mobile"=>"TJ70066318.png"  
                                                    ],
                                            ],
                                        "image_default"=>"backdrop_menu.jpg"    
                                        ];
        $data['data_item_beverages'] = [
                                        "title_category"=>"beverages",
                                        "item"=>[
                                                    [
                                                        "product_name_eng"=>"sunshine coco",
                                                        "product_name_ind"=>"kelapa bersinar",
                                                        "product_img"=>"backdrop_menu.jpg",
                                                        "product_img_mobile"=>"TJ700663.png"
                                                    ],
                                                    [
                                                        "product_name_eng"=>"honey rambutan",
                                                        "product_name_ind"=>"madu rambutan",  
                                                        "product_img"=>"",
                                                        "product_img_mobile"=>"TJ700663.png"
                                                    ],
                                                    [
                                                        "product_name_eng"=>"tea house blend",
                                                        "product_name_ind"=>"teh rumah campuran",   
                                                        "product_img"=>"coriander-powder.jpg",
                                                        "product_img_mobile"=>"TJ700663.png" 
                                                    ],
                                                    [
                                                        "product_name_eng"=>"honey hevea",
                                                        "product_name_ind"=>"madu karet",   
                                                        "product_img"=>"",
                                                        "product_img_mobile"=>"TJ700663.png" 
                                                    ],
                                                    [
                                                        "product_name_eng"=>"vanilla EZ mix",
                                                        "product_name_ind"=>"vanilla EZ campur",   
                                                        "product_img"=>"",
                                                        "product_img_mobile"=>"TJ700663.png" 
                                                    ],
                                                    [
                                                        "product_name_eng"=>"honey randu",
                                                        "product_name_ind"=>"madu randu",  
                                                        "product_img"=>"",
                                                        "product_img_mobile"=>"TJ700663.png"  
                                                    ],
                                                    [
                                                        "product_name_eng"=>"green tea powder",
                                                        "product_name_ind"=>"teh hijau bubuk", 
                                                        "product_img"=>"",
                                                        "product_img_mobile"=>"TJ700663.png"   
                                                    ],
                                                    [
                                                        "product_name_eng"=>"coconut sugar organic",
                                                        "product_name_ind"=>"gula kelapa organik",  
                                                        "product_img"=>"",
                                                        "product_img_mobile"=>"TJ700663.png"  
                                                    ],
                                                    [
                                                        "product_name_eng"=>"turmeric powder",
                                                        "product_name_ind"=>"kunyit bubuk",   
                                                        "product_img"=>"",
                                                        "product_img_mobile"=>"TJ700663.png" 
                                                    ],
                                                    [
                                                        "product_name_eng"=>"coconut sugar",
                                                        "product_name_ind"=>"gula kelapa",
                                                        "product_img"=>"",
                                                        "product_img_mobile"=>"TJ700663.png"    
                                                    ],
                                                    [
                                                        "product_name_eng"=>"ginger powder",
                                                        "product_name_ind"=>"jahe bubuk",   
                                                        "product_img"=>"",
                                                        "product_img_mobile"=>"TJ700663.png" 
                                                    ],
                                                    [
                                                        "product_name_eng"=>"clove bud",
                                                        "product_name_ind"=>"cengkeh", 
                                                        "product_img"=>"",
                                                        "product_img_mobile"=>"TJ700663.png"   
                                                    ],
                                                    [
                                                        "product_name_eng"=>"arenga sugar",
                                                        "product_name_ind"=>"gula aren", 
                                                        "product_img"=>"",
                                                        "product_img_mobile"=>"TJ700663.png"   
                                                    ],
                                                    [
                                                        "product_name_eng"=>"cold brew",
                                                        "product_name_ind"=>"minuman dingin", 
                                                        "product_img"=>"",
                                                        "product_img_mobile"=>"TJ700663.png"   
                                                    ],
                                                    [
                                                        "product_name_eng"=>"cassia ground powder",
                                                        "product_name_ind"=>"kayu manis bubuk",  
                                                        "product_img"=>"",
                                                        "product_img_mobile"=>"TJ700663.png"  
                                                    ],
                                                    [
                                                        "product_name_eng"=>"cassia stick 8 cm",
                                                        "product_name_ind"=>"kayu manis batang 8 cm",   
                                                        "product_img"=>"",
                                                        "product_img_mobile"=>"TJ700663.png" 
                                                    ],
                                            ],
                                        "image_default"=>"backdrop_menu.jpg" 
                                        ];   
        $data['data_item_desserts'] = [
                                        "title_category"=>"desserts",
                                        "item"=>[
                                                    [
                                                        "product_name_eng"=>"honey rambutan",
                                                        "product_name_ind"=>"madu rambutan",
                                                        "product_img"=>"backdrop_menu.jpg",
                                                        "product_img_mobile"=>"TJ700663.png"
                                                    ],
                                                    [
                                                        "product_name_eng"=>"dessicated coconut",
                                                        "product_name_ind"=>"madu rambutan",  
                                                        "product_img"=>"",
                                                        "product_img_mobile"=>"TJ700663.png" 
                                                    ],
                                                    [
                                                        "product_name_eng"=>"honey hevea",
                                                        "product_name_ind"=>"teh rumah campuran",   
                                                        "product_img"=>"coriander-powder.jpg",
                                                        "product_img_mobile"=>"TJ700663.png" 
                                                    ],
                                                    [
                                                        "product_name_eng"=>"arenga sugar",
                                                        "product_name_ind"=>"madu karet",   
                                                        "product_img"=>"",
                                                        "product_img_mobile"=>"TJ700663.png" 
                                                    ],
                                                    [
                                                        "product_name_eng"=>"honey randu",
                                                        "product_name_ind"=>"vanilla EZ campur",   
                                                        "product_img"=>"",
                                                        "product_img_mobile"=>"TJ700663.png" 
                                                    ],
                                                    [
                                                        "product_name_eng"=>"cassia ground powder",
                                                        "product_name_ind"=>"madu randu",  
                                                        "product_img"=>"",
                                                        "product_img_mobile"=>"TJ700663.png"  
                                                    ],
                                                    [
                                                        "product_name_eng"=>"coconut sugar organic",
                                                        "product_name_ind"=>"teh hijau bubuk", 
                                                        "product_img"=>"",
                                                        "product_img_mobile"=>"TJ700663.png"   
                                                    ],
                                                    [
                                                        "product_name_eng"=>"cassia stick 8 cm",
                                                        "product_name_ind"=>"gula kelapa organik",  
                                                        "product_img"=>"",
                                                        "product_img_mobile"=>"TJ700663.png"  
                                                    ],
                                                    [
                                                        "product_name_eng"=>"coconut sugar",
                                                        "product_name_ind"=>"kunyit bubuk",   
                                                        "product_img"=>"",
                                                        "product_img_mobile"=>"TJ700663.png" 
                                                    ],
                                                    [
                                                        "product_name_eng"=>"vanilla EZ mix",
                                                        "product_name_ind"=>"gula kelapa",
                                                        "product_img"=>"",
                                                        "product_img_mobile"=>"TJ700663.png"    
                                                    ],
                                                    [
                                                        "product_name_eng"=>"coconut milk powder 60% FAT",
                                                        "product_name_ind"=>"jahe bubuk",   
                                                        "product_img"=>"",
                                                        "product_img_mobile"=>"TJ700663.png" 
                                                    ],
                                                    [
                                                        "product_name_eng"=>"green tea powder",
                                                        "product_name_ind"=>"cengkeh", 
                                                        "product_img"=>"",
                                                        "product_img_mobile"=>"TJ700663.png"   
                                                    ],
                                                    [
                                                        "product_name_eng"=>"coconut milk 40% FAT",
                                                        "product_name_ind"=>"gula aren", 
                                                        "product_img"=>"",
                                                        "product_img_mobile"=>"TJ700663.png"   
                                                    ]
                                            ],
                                        "image_default"=>"backdrop_menu.jpg" 
                                        ];                               
        $data["data_item_cart"] =[
                                    [
                                        "cart_img"=>"product_cart.png",
                                        "product_name"=>"coconut sugar organic",
                                        "quantity"=>1,
                                        "price"=>number_format(80000,0,",",".")
                                    ],
                                    [
                                        "cart_img"=>"product_cart.png",
                                        "product_name"=>"coconut sugar organic",
                                        "quantity"=>1,
                                        "price"=>number_format(80000,0,",",".")
                                    ],
                                    [
                                        "cart_img"=>"product_cart.png",
                                        "product_name"=>"coconut sugar organic",
                                        "quantity"=>1,
                                        "price"=>number_format(80000,0,",",".")
                                    ],
                                    ];                            
        return $data;                            
    }
    private function getFooter(){
        $data['img_footer'] = "logo_footer.png";
        $data['information'] = [
                                [
                                    "footer_section_name"=>"information",
                                    "sub_section"=>[
                                                        [
                                                            "name_section"=>"about us",
                                                            "section_link"=>"#"
                                                        ],
                                                        [
                                                            "name_section"=>"terms of use",
                                                            "section_link"=>"#"
                                                        ],
                                                        [
                                                            "name_section"=>"contact us",
                                                            "section_link"=>"contact_us"
                                                        ],
                                                        [
                                                            "name_section"=>"blog",
                                                            "section_link"=>"#"
                                                        ],
                                                    ]
                                    ],
                                [
                                    "footer_section_name"=>"shopping guide",
                                    "sub_section"=>[
                                                        [
                                                            "name_section"=>"FAQs",
                                                            "section_link"=>"#"
                                                        ],
                                                        [
                                                            "name_section"=>"payment",
                                                            "section_link"=>"#"
                                                        ],
                                                        [
                                                            "name_section"=>"track your order",
                                                            "section_link"=>"#"
                                                        ],
                                                    ]
                                    ],    
                                ];
        $data['subscribe'] = [
                                "subscribe_title"=>"keep updated",
                                "subscribe_descr"=>"sign up for email and never miss the latest products, news, events, promotion and more",
                                "subscribe_img"=>"envelope.svg",
                                "subscribe_btn_name"=>"subscribe"
                                ];
        $data['payment'] = [
                                "payment_title"=>"payment",
                                "payment_img"=>[
                                                    [
                                                       "img_name"=>"bca.png" 
                                                        ],
                                                    [
                                                       "img_name"=>"bni.png" 
                                                        ],
                                                    [
                                                       "img_name"=>"bri.png" 
                                                        ],
                                                    [
                                                       "img_name"=>"permata.png" 
                                                        ], 
                                                    [
                                                       "img_name"=>"hana_bank.png" 
                                                        ],    
                                                    [
                                                       "img_name"=>"danamon.png" 
                                                        ],     
                                                    [
                                                       "img_name"=>"cimb.png" 
                                                        ],     
                                                    [
                                                       "img_name"=>"maybank.png" 
                                                        ],     
                                                    [
                                                       "img_name"=>"alfamart.png" 
                                                        ],     
                                                    [
                                                       "img_name"=>"alfamidi.png" 
                                                        ],     
                                                    [
                                                       "img_name"=>"indomart.png" 
                                                        ],     
                                                    ]
                                ];    
        $data['shipment'] = [
                                "shipment_title"=>"shipment",
                                "shipment_img"=>[
                                                    [
                                                       "img_name"=>"tiki.png" 
                                                        ],
                                                    [
                                                       "img_name"=>"rex.png" 
                                                        ],               
                                                    ]
                                ];
        $data['information_bottom'] = [
                                        "email_contact"=>"hello@haldinfoods.com",
                                        "phone"=>"+62 21 8998 1788",
                                        "working_hour"=>"Monday - Friday | 07.30 - 16.30",
                                        "year"=>2018
                                        ];                            
        return $data;
    }
    private function dataSideFilter(){
        $data['side_filter'] = [
                                [
                                    "filter_name"=>"quantity",
                                    "filter_id"=>"quantity",
                                    "filter_array"=>"quantity[]",
                                    "filters"=>[
                                                        [
                                                            "filter_variable"=>"1 L"
                                                        ],
                                                        [
                                                            "filter_variable"=>"5 L"
                                                        ],
                                                        [
                                                            "filter_variable"=>"1 Kg"
                                                        ],
                                                        [
                                                            "filter_variable"=>"5 Kg"
                                                        ],
                                                            ]
                                ],
                                [
                                    "filter_name"=>"price",
                                    "filter_id"=>"price",
                                    "filter_array"=>"price[]",
                                    "filters"=>[
                                                        [
                                                            "filter_variable"=>"under ".number_format(250000,0,",",".")
                                                        ],
                                                        [
                                                            "filter_variable"=>number_format(250000,0,",",".")." - ".number_format(500000,0,",",".") 
                                                        ],
                                                        [
                                                            "filter_variable"=>number_format(500000,0,",",".")." - ".number_format(1000000,0,",",".") 
                                                        ],
                                                        [
                                                            "filter_variable"=>"above ".number_format(1000000,0,",",".")
                                                        ],
                                                            ]
                                ],
                                    ];  
        return $data;
    }




    
    



}
