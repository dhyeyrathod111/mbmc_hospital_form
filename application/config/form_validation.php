<?php 

$config = array(
                 'marriage' => array(
                                    array(
						                     'field'   => 'app_id',
						                     'label'   => 'Application ID',
						                     'rules'   => 'required'
						                  ),
						               array(
						                     'field'   => 'application_no',
						                     'label'   => 'application No',
						                     'rules'   => 'required'
						                  ),
						               array(
						                     'field'   => 'marriage_date',
						                     'label'   => 'Marriage Date',
						                     'rules'   => 'required|max_length[25]'
						                  ),   
						               array(
						                     'field'   => 'husband_name',
						                     'label'   => 'Husband Name',
						                     'rules'   => 'required|max_length[25]'
						                  ),
						               array(
						                     'field'   => 'husband_age',
						                     'label'   => 'Husband Age',
						                     'rules'   => 'required|max_length[10]|integer'
						                  ),
						               array(
						                     'field'   => 'husband_religious',
						                     'label'   => 'Husband Religious',
						                     'rules'   => 'required|max_length[50]'
						                  ),
						               array(
						                     'field'   => 'husband_marriage_status',
						                     'label'   => 'Husband Marriage Status',
						                     'rules'   => 'required'
						                  ),
						               array(
						                     'field'   => 'husband_address',
						                     'label'   => 'Husband Address',
						                     'rules'   => 'required|max_length[200]'
						                  ),
						               array(
						                     'field'   => 'wife_name',
						                     'label'   => 'Wife Name',
						                     'rules'   => 'required|max_length[25]'
						                  ),
						               array(
						                     'field'   => 'wife_age',
						                     'label'   => 'Wife Age',
						                     'rules'   => 'required|max_length[10]|integer'
						                  ),
						               array(
						                     'field'   => 'wife_religious',
						                     'label'   => 'Wife Religious',
						                     'rules'   => 'required|max_length[25]'
						                  ),
						               array(
						                     'field'   => 'wife_marriage_status',
						                     'label'   => 'wife Marriage Status',
						                     'rules'   => 'required|max_length[10]'
						                  ),
						               array(
						                     'field'   => 'wife_address',
						                     'label'   => 'wife Address',
						                     'rules'   => 'required|max_length[200]'
						                  ),
						               array(
						                     'field'   => 'priest_name',
						                     'label'   => 'Priest Name',
						                     'rules'   => 'required|max_length[25]'
						                  ),
						               array(
						                     'field'   => 'priest_age',
						                     'label'   => 'Priest Age',
						                     'rules'   => 'required|max_length[10]|integer'
						                  ),
						               array(
						                     'field'   => 'priest_religious',
						                     'label'   => 'wife Age',
						                     'rules'   => 'required|max_length[25]'
						                  ),
						               array(
						                     'field'   => 'priest_address',
						                     'label'   => 'Priest Address',
						                     'rules'   => 'required|max_length[25]'
						                  ),

						               
						               // array(
						               //       'field'   => 'husband_aadhaar_card_name',
						               //       'label'   => 'Husband Aadhaar Card',
						               //       'rules'   => 'required'
						               //    ),
						               // array(
						               //       'field'   => 'wife_aadhaar_card_name',
						               //       'label'   => 'Wife Aadhaar Card',
						               //       'rules'   => 'required'
						               //    ),
						               // array(
						               //       'field'   => 'first_witness_id_proof_name',
						               //       'label'   => 'First Witness Id Proof',
						               //       'rules'   => 'required'
						               //    ),
						               // array(
						               //       'field'   => 'second_witness_id_proof_name',
						               //       'label'   => 'second Witness Id Proof',
						               //       'rules'   => 'required'
						               //    ),
						               // array(
						               //       'field'   => 'third_witness_id_proof_name',
						               //       'label'   => 'third Witness Id Proof',
						               //       'rules'   => 'required'
						               //    ),
						               // array(
						               //       'field'   => 'bill_name',
						               //       'label'   => 'bill Name',
						               //       'rules'   => 'required'
						               //    ),
						               // array(
						               //       'field'   => 'lagan_patrika_name',
						               //       'label'   => 'Lagan Patrika',
						               //       'rules'   => 'required'
						               //    ),
						               // array(
						               //       'field'   => 'ration_card_name',
						               //       'label'   => 'Ration card',
						               //       'rules'   => 'required'
						               //    ),
                                    ),
                 'email' => array(
                                    array(
                                            'field' => 'emailaddress',
                                            'label' => 'EmailAddress',
                                            'rules' => 'required|valid_email'
                                         ),
                                    array(
                                            'field' => 'name',
                                            'label' => 'Name',
                                            'rules' => 'required|alpha'
                                         ),
                                    array(
                                            'field' => 'title',
                                            'label' => 'Title',
                                            'rules' => 'required'
                                         ),
                                    array(
                                            'field' => 'message',
                                            'label' => 'MessageBody',
                                            'rules' => 'required'
                                         )
                                    )                          
               );