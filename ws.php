<?php

header('Content-type: application/json');

include('./functions.php');
include('./db.php');
require_once('./sessions.php');

/*
*
* Rate limiter & domain lock
* 
*/

// if (rateLimit_PerSecond()) {
//     http_response_code(429);
//     die();
// }

// if (rateLimit_PerDay() == false) {
//     http_response_code(429);
//     die();
// }

// if ($_SERVER['QUERY_STRING'] != 'page=get-user-id') {
//     if (domainLock() == false) {
//         // Show specific error page, then die
//         die();
//     }
// }

/*
*
* Session starts and is automatically 400 resp code
* 
*/

session_start();
http_response_code(400);

// COMMENTS FOR JOHN
//
// If user is not logged in, session is not set for login.
if (!isset($_SESSION['is-logged-in']))  {
    $_SESSION['is-logged-in'] = false;
}

/*
*
* Switch case
* resp_body to be removed for deployment
* 
*/

// COMMENTS FOR JOHN
// Question 15
//
// This is the switch case that checks all GET/POST structures
if (isset($_GET['page'])) {
    switch($_GET['page']) {

        // Get user ID
        case 'get-user-id':
            // COMMENTS FOR JOHN
            // Question 14
            //
            // The ($_SESSION['is-logged-in']) is scattered through the switch case where required.
            // If it fails, the user cannot pass through the switch case.
            // Note ($_SESSION['user']) is also passed through to validate userID.
            if ($_SESSION['is-logged-in']) {
                $res = (db_getUserID($_SESSION['user']));
                if (is_array($res)) {
                    $resp_code = http_response_code(200);
                    $resp_body = ['get-user-id' => true];
                    $_SESSION['user_id'] = array_values($res)[0];
                } else {
                    $resp_code = http_response_code(400);
                    $resp_body = ['get-user-id' => 'array error'];
                } 
            } else {
                $resp_code = http_response_code(403);
                $resp_body = ['get-user-id' => 'not logged in'];
            }
            break;

        // Register
        case 'register':
            if ($_SESSION['is-logged-in'] == false) {
                if (isset($_POST['first-name'], $_POST['last-name'], $_POST['email'], $_POST['password'])) {
                    if (email_regexCheck($_POST['email']) && (password_regexCheck($_POST['password'])) && (letter_regexCheck($_POST['first-name'])) && (letter_regexCheck($_POST['last-name']))) {
                        if (db_registerEmailCheck($_POST['email'])) {
                            if (db_doRegister($_POST['first-name'], $_POST['last-name'], $_POST['email'], $_POST['password'])) {
                                $resp_code = http_response_code(201);
                                $resp_body = ['register' => true];
                                ($_SESSION['is-logged-in'] === true);
                                $_SESSION['user'] = $_POST['email'];
                                $_SESSION['role'] = 'user';
                            } else {
                                $resp_code = http_response_code(400);
                                $resp_body = ['register' => 'database fail'];
                            }
                        } else {
                            $resp_code = http_response_code(400);
                            $resp_body = ['register' => 'email already exists']; 
                        }
                    } else {
                        $resp_code = http_response_code(400);
                        $resp_body = ['register' => 'validity fail'];
                    }
                } else {
                    $resp_code = http_response_code(400);
                    $resp_body = ['register' => 'post error'];
                }
            } else {
                $resp_code = http_response_code(400);
                $resp_body = ['register' => 'already logged in'];
            }
            break;

        // Login
        case 'login':
            // The below 'if' commented out for ease of testing only
            // if ($_SESSION['is-logged-in'] == false) {
                if (isset($_POST['email'], $_POST['password'])) {
                    if (email_regexCheck($_POST['email']) && (password_regexCheck($_POST['password']))) {
                        if (db_login($_POST['email'], $_POST['password'])) {
                            $resp_code = http_response_code(200);
                            $resp_body = ['login' => true];
                            $_SESSION['is-logged-in'] = true;
                            $_SESSION['user'] = $_POST['email'];
                        } else {
                            $resp_code = http_response_code(400);
                            $resp_body = false;
                        }
                    } else {
                        $resp_code = http_response_code(400);
                        $resp_body = ['login' => 'validiy fail'];
                    }
                } else {
                    $resp_code = http_response_code(400);
                    $resp_body = ['login' => 'post error'];
                }
            // } else {
            //     $resp_code = http_response_code(400);
            //     $resp_body = ['login' => 'already logged in'];
            // }
            break;
        
        // Login check
        case 'is-logged-in':
            if (isUserLoggedIn() == true) {
                $resp_code = http_response_code(200);
                $resp_body = ['is-logged-in' => true];
            } else {
                $resp_code = http_response_code(403);
                $resp_body = ['is-logged-in' => false];
            }
            break;

        // Update reg
        case 'update-reg':
            if ($_SESSION['is-logged-in']) {
                if (isset($_POST['first-name'], $_POST['last-name'], $_POST['email'])) {
                    if (email_regexCheck($_POST['email']) && (letter_regexCheck($_POST['first-name'], $_POST['last-name']))) {
                        if (db_updateReg($_SESSION['user_id'], $_POST['first-name'], $_POST['last-name'], $_POST['email'])) {
                            $resp_code = http_response_code(200);
                            $resp_body = ['update-reg' => true];
                        } else {
                            $resp_code = http_response_code(400);
                            $resp_body = ['update-reg' => 'database fail'];
                        }
                    } else {
                        $resp_code = http_response_code(400);
                        $resp_body = ['update-reg' => 'validity fail'];
                    }
                } else {
                    $resp_code = http_response_code(400);
                    $resp_body = ['update-reg' => 'post error'];
                }
            } else {
                $resp_code = http_response_code(400);
                $resp_body = ['update-reg' => 'not logged in'];
            }
            break;

        // Logout
        case 'logout':
            doLogout();
            $resp_code = http_response_code(200);
            $resp_body = ['logout' => true];
            break;             

        // Add animal
        case 'add-animal':
            if (isUserLoggedIn()) {
                if (isset($_POST['name'], $_POST['notes'], $_POST['gender'], $_POST['species_id'], $_POST['maturity'])) {
                    if (letter_regexCheck($_POST['name']) && (letter_regexCheck($_POST['notes'])) && (letter_regexCheck($_POST['gender'])) && (letter_regexCheck($_POST['maturity'])) && (number_regexCheck($_POST['species_id']))) {
                        if (db_addAnimal($_SESSION['user_id'], $_POST['name'], $_POST['notes'], $_POST['gender'], $_POST['species_id'], $_POST['maturity'])) {
                            $resp_code = http_response_code(201);
                            $resp_body = ['add-animal' => true];
                        } else {
                            $resp_code = http_response_code(400);
                            $resp_body = ['add-animal' => 'db error'];
                        }
                    } else {
                        $resp_code = http_response_code(400);
                        $resp_body = ['add-animal' => 'validity fail'];
                    }
                } else {
                    $resp_code = http_response_code(400);
                    $resp_body = ['add-animal' => 'post error'];
                }
            } else {
                $resp_code = http_response_code(403);
                $resp_body = ['add-animal' => 'not logged in'];
            }
            break;

        // View animals
        case 'view-animals':
            if (isUserLoggedIn()) {
                $res = db_viewAnimals($_SESSION['user_id'], $_POST['type_name']);
                if (is_array($res)) {
                    $resp_code = http_response_code(200);
                    $resp_body = ['view-animals' => true];
                    echo json_encode($res);
                } else {
                    $resp_code = http_response_code(400);
                    $resp_body = ['view-animals' => 'db error'];
                }
            } else {
                $resp_code = http_response_code(400);
                $resp_body = ['view-animals' => 'user id not provided'];
            }            
            break;

        // View species
        case 'view-species':
            if (isUserLoggedIn()) {
                $res = db_viewSpecies($_SESSION['user_id'], $_POST['animal_type_id']);
                if (is_array($res)) {
                    $resp_code = http_response_code(200);
                    $resp_body = ['view-species' => true];
                    echo json_encode($res);
                } else {
                    $resp_code = http_response_code(400);
                    $resp_body = ['view-species' => 'db error'];
                }
            } else {
                $resp_code = http_response_code(400);
                $resp_body = ['view-species' => 'user id not provided'];
            }            
            break;

        // Get species ID
        case 'get-species-id':
            if (isUserLoggedIn()) {
                if (isset($_POST['species'])) {
                    if (letter_regexCheck($_POST['species'])) {
                        $res = db_getSpeciesID($_SESSION['user_id'], $_POST['species']);
                        if (is_array($res)) {
                            $resp_code = http_response_code(200);
                            $resp_body = ['get-species-id' => true];
                            echo json_encode($res);
                        } else {
                            $resp_code = http_response_code(400);
                            $resp_body = ['get-species-id' => 'db error'];
                        }
                    } else {
                        $resp_code = http_response_code(400);
                        $resp_body = ['get-species-id' => 'validity fail'];
                    }    
                } else {
                    $resp_code = http_response_code(400);
                    $resp_body = ['get-species-id' => 'species not provided'];
                }    
            } else {
                $resp_code = http_response_code(400);
                $resp_body = ['get-species-id' => 'not logged in'];
            }            
            break;

        // View one animal
        case 'view-one-animal':
            if (isUserLoggedIn()) {
                if (isset($_POST['animal_id'])) {
                    if (number_regexCheck($_POST['animal_id'])) {
                        $res = db_viewOneAnimal($_SESSION['user_id'], $_POST['animal_id']);
                        if (is_array($res)) {
                            $resp_code = http_response_code(200);
                            $resp_body = ['view-one-animal' => true];
                            echo json_encode($res);
                        } else {
                            $resp_code = http_response_code(400);
                            $resp_body = ['view-one-animal' => 'db error'];
                        }
                    } else {
                        $resp_code = http_response_code(400);
                        $resp_body = ['view-one-animal' => 'validity fail'];
                    }
                } else {
                    $resp_code = http_response_code(400);
                    $resp_body = ['view-one-animal' => 'post error'];
                }   
            } else {
                $resp_code = http_response_code(400);
                $resp_body = ['view-one-animal' => 'not logged in'];
            }         
            break;

        // Get coordinates
        case 'get-coordinates':
            if (isUserLoggedIn()) {
                if (isset($_POST['animal_id'])) {
                    if (number_regexCheck($_POST['animal_id'])) {
                        $res = db_viewAnimalPerCoordinates($_SESSION['user_id'], $_POST['animal_id']);
                        if (is_array($res)) {
                            $resp_code = http_response_code(200);
                            $resp_body = ['get-coordinates' => true];
                            echo json_encode($res);
                        } else {
                            $resp_code = http_response_code(400);
                            $resp_body = ['get-coordinates' => 'db error'];
                        }
                    } else {
                        $resp_code = http_response_code(400);
                        $resp_body = ['get-coordinates' => 'validity fail'];
                    }
                } else {
                    $resp_code = http_response_code(400);
                    $resp_body = ['get-coordinates' => 'post error'];
                }   
            } else {
                $resp_code = http_response_code(400);
                $resp_body = ['get-coordinates' => 'not logged in'];
            }         
            break;

        // View animals per species
        case 'animals-per-species':
            if (isUserLoggedIn()) {
                if (isset($_POST['species'])) {
                    if (letter_regexCheck($_POST['species'])) {
                        $res = db_viewAnimalsPerSpecies($_SESSION['user_id'], $_POST['species']);
                        if (is_array($res)) {
                            $resp_code = http_response_code(200);
                            $resp_body = ['animals-per-species' => true];
                            echo json_encode($res);
                        } else {
                            $resp_code = http_response_code(400);
                            $resp_body = ['animals-per-species' => 'db error'];
                        }
                    } else {
                        $resp_code = http_response_code(400);
                        $resp_body = ['animals-per-species' => 'validity fail'];
                    }
                } else {
                    $resp_code = http_response_code(400);
                    $resp_body = ['animals-per-species' => 'post error'];
                }   
            } else {
                $resp_code = http_response_code(400);
                $resp_body = ['animals-per-species' => 'not logged in'];
            }         
            break;

        // Species count
        case 'species-count':
            if (isUserLoggedIn()) {
                if (isset($_POST['species'])) {
                    if (letter_regexCheck($_POST['species'])) {
                        $res = db_speciesCount($_SESSION['user_id'], $_POST['species']);
                        if (is_array($res)) {
                            $resp_code = http_response_code(200);
                            $resp_body = ['species-count' => true];
                            echo json_encode($res);
                        } else {
                            $resp_code = http_response_code(400);
                            $resp_body = ['species-count' => 'db error'];
                        }
                    } else {
                        $resp_code = http_response_code(400);
                        $resp_body = ['species-count' => 'validity fail'];
                    }
                } else {
                    $resp_code = http_response_code(400);
                    $resp_body = ['species-count' => 'post error'];
                }   
            } else {
                $resp_code = http_response_code(400);
                $resp_body = ['species-count' => 'not logged in'];
            }         
            break;

        // Edit animal
        case 'edit-animal':
            if (isUserLoggedIn()) {
                if (isset($_POST['animal_id'], $_POST['species_id'], $_POST['name'], $_POST['notes'], $_POST['gender'])) {
                    if (number_regexCheck($_POST['animal_id'], $_POST['species']) && letter_regexCheck($_POST['name'], $_POST['notes'], $_POST['gender'])) {
                        if (db_editAnimal($_POST['animal_id'], $_POST['species_id'], $_SESSION['user_id'], $_POST['name'], $_POST['notes'], $_POST['gender'])) {
                            $resp_code = http_response_code(200);
                            $resp_body = ['edit-animal' => true];
                        } else {
                            $resp_code = http_response_code(400);
                            $resp_body = ['edit-animal' => 'db fail'];
                        }
                    } else {
                        $resp_code = http_response_code(400);
                        $resp_body = ['edit-animal' => 'empty value/s'];
                    }
                } else {
                    $resp_code = http_response_code(400);
                    $resp_body = ['edit-animal' => 'post error'];
                }
            } else {
                $resp_code = http_response_code(403);
                $resp_body = ['edit-animal' => 'not logged in'];
            }
            break;

        // Delete animal
        case 'delete-animal':
            if (isUserLoggedIn()) {
                if (isset($_POST['animal_id'])) {
                    if (number_regexCheck($_POST['animal_id'])) {
                        if (db_deleteAnimal($_POST['animal_id'])) {
                            $resp_code = http_response_code(200);
                            $resp_body = ['delete-animal' => true];
                        } else {
                            $resp_code = http_response_code(400);
                            $resp_body = ['delete-animal' => 'db fail'];
                        }
                    } else {
                        $resp_code = http_response_code(400);
                        $resp_body = ['delete-animal' => 'validity fail'];  
                    }
                } else {
                    $resp_code = http_response_code(400);
                    $resp_body = ['delete-animal' => 'post fail'];
                }
            } else {
                $resp_code = http_response_code(403);
                $resp_body = ['delete-animal' => 'not logged in'];
            }
            break;

        // Add log
        case 'add-log':
            if (isUserLoggedIn()) {
                if (isset($_POST['title'], $_POST['text'], $_POST['animal_id'])) {
                    if (letter_regexCheck($_POST['title']) && (letter_regexCheck($_POST['text'])) && (number_regexCheck($_POST['animal_id']))) {
                        if (db_addLog($_POST['title'], $_POST['text'], $_POST['animal_id'])) {
                            $resp_code = http_response_code(200);
                            $resp_body = ['add-log' => true];
                        } else {
                            $resp_code = http_response_code(400);
                            $resp_body = ['add-log' => 'db fail'];
                        }
                    } else {
                        $resp_code = http_response_code(400);
                        $resp_body = ['add-log' => 'validity fail'];
                    }
                } else {
                    $resp_code = http_response_code(400);
                    $resp_body = ['add-log' => 'post fail'];
                }
            } else {
                $resp_code = http_response_code(403);
                $resp_body = ['add-log' => 'not logged in'];
            }
            break;

        // Delete account
        case 'delete-account':
            if (isUserLoggedIn()) {
                if (db_deleteAccount($_SESSION['user_id'])) {
                    $resp_code = http_response_code(200);
                    $resp_body = ['delete-account' => true];
                } else {
                    $resp_code = http_response_code(400);
                    $resp_body = ['delete-account' => 'db fail'];
                }
            } else {
                $resp_code = http_response_code(400);
                $resp_body = ['delete-account' => 'value/s not provided'];
            }
            break;

        // View all species
        case 'view-all-species':
            if ($_SESSION['is-logged-in']) {
                $res = (db_viewAllSpecies());
                if (is_array($res)) {
                    $resp_code = http_response_code(200);
                    $resp_body = ['view-all-species' => true];
                    echo json_encode($res);
                } else {
                    $resp_code = http_response_code(400);
                    $resp_body = ['view-all-species' => 'array error'];
                } 
            } else {
                $resp_code = http_response_code(403);
                $resp_body = ['view-all-specie' => 'not logged in'];
            }
            break;

        // Check if admin
        case 'check-if-admin':
            if ($_SESSION['is-logged-in']) {
                if (db_isAdmin($_SESSION['user'])) {
                    $resp_code = http_response_code(200);
                    $resp_body = ['check-if-admin' => true];
                } else {
                    $resp_code = http_response_code(400);
                    $resp_body = ['check-if-admin' => 'db fail'];
                }
            } else {
                $resp_code = http_response_code(400);
                $resp_body = ['check-if-admin' => 'not logged in'];
            }
            break;

        // Edit first name
        case 'edit-first-name':
            if ($_SESSION['is-logged-in']) {
                if (isset($_POST['first-name'])) {
                    if (letter_regexCheck($_POST['first-name'], $_POST['last-name'])) {
                        if (db_editFirstName($_SESSION['user_id'], $_POST['first-name'])) {
                            $resp_code = http_response_code(200);
                            $resp_body = ['edit-first-name' => true];
                        } else {
                            $resp_code = http_response_code(400);
                            $resp_body = ['edit-first-name' => 'database fail'];
                        }
                    } else {
                        $resp_code = http_response_code(400);
                        $resp_body = ['edit-first-name' => 'validity fail'];
                    }
                } else {
                    $resp_code = http_response_code(400);
                    $resp_body = ['edit-first-name' => 'post error'];
                }
            } else {
                $resp_code = http_response_code(400);
                $resp_body = ['edit-first-name' => 'not logged in'];
            }
            break;

        // File upload
        // case 'file-upload':
        //     if (isset($_POST['image'])) {
        //         $resp_code = http_response_code(200);
        //         $resp_body = ['file-upload' => true];
        //     } else {
        //         $resp_code = http_response_code(400);
        //         $resp_body = ['file-upload' => 'not set'];
        //     }
    }
}

/*
*
* Log each user interaction
* 
*/

// db_log(session_id(), ($_SERVER['QUERY_STRING']), http_response_code(), $_SESSION['user'], $_SERVER['REMOTE_ADDR'], $_SESSION['role']);

/*
*
* Debugging
* 
*/

// echo json_encode(http_response_code());
// echo json_encode($resp_body);
// echo json_encode($_SESSION['user']);
// echo json_encode($_SERVER);
// echo ($_SESSION['user_id']);
// echo json_encode($res);