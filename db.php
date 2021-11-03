<?php

// COMMENTS FOR JOHN
// Question 12
//
// This db.php file contains all connection info and queries that are required.
// These have been placed into their own file for seperation of concerns.

$dbURI = 'mysql:host=localhost;port=8889;dbname=wildlife-watcher';
$dbconn = new PDO($dbURI, 'user1', 'user1');

// Register
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
function db_doRegister ($firstname, $lastname, $email, $password) {
    global $dbconn;
    $sql = "INSERT INTO users (first_name, last_name, email, pass) 
            VALUES (:fn, :ln, :e, :p)";
    $stmt = $dbconn->prepare($sql);
    $stmt->bindParam(':fn', $firstname, PDO::PARAM_STR);
    $stmt->bindParam(':ln', $lastname, PDO::PARAM_STR);
    $stmt->bindParam(':e', $email, PDO::PARAM_STR);
    $stmt->bindParam(':p', $password, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() == 1) { 
        return true;
    }
    return false;
}

// Check db for duplicate email on reg
function db_registerEmailCheck ($email) {
    global $dbconn;
    $sql = "SELECT email FROM users WHERE email = :e";
    $stmt = $dbconn->prepare($sql);
    $stmt->bindParam(':e', $email, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() >= 1) { 
        return false;
    }
    return true;
}

// Login
if (password_verify($password, $hashed_password)) {
    function db_login ($email, $password) {
        global $dbconn;
        $sql = "SELECT * FROM users WHERE email = :e AND pass = :p";
        $stmt = $dbconn->prepare($sql);
        $stmt->bindParam(':e', $email, PDO::PARAM_STR);
        $stmt->bindParam(':p', $password, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->rowCount() == 1) { 
            $_SESSION['is-logged-in'] = true;
            return true;
        }
        return false;
    }
}

// Check db for duplicate email on update reg
function db_updateRegisterEmailCheck ($email, $id) {
    global $dbconn;
    $sql = "SELECT email FROM users WHERE email = :e and user_id = :id";
    $stmt = $dbconn->prepare($sql);
    $stmt->bindParam(':e', $email, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() >= 1) { 
        return false;
    }
    return true;
}

// Get user ID of user logged in
function db_getUserID ($email) {
    global $dbconn;
    $sql = "SELECT user_id FROM users WHERE email = :e";
    $stmt = $dbconn->prepare($sql);
    $stmt->bindParam(':e', $email, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() == 1) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    return false;
}

// Store user logs in db
function db_log ($session_id, $action, $resp_code, $user, $ip, $role) {
    global $dbconn;
    $sql = "INSERT INTO user_logs (session_id, action, user_resp_code, user, user_ip, role) 
            VALUES (:sid, :a, :urc, :u, :ip, :r)";
    $stmt = $dbconn->prepare($sql);
    $stmt->bindParam(':sid', $session_id, PDO::PARAM_STR);
    $stmt->bindParam(':a', $action, PDO::PARAM_STR);
    $stmt->bindParam(':urc', $resp_code, PDO::PARAM_STR);
    $stmt->bindParam(':u', $user, PDO::PARAM_STR);
    $stmt->bindParam(':ip', $ip, PDO::PARAM_STR);
    $stmt->bindParam(':r', $role, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() > 0) { 
        return true;
    }
    return false;
}

// Add animal
function db_addAnimal($user_id, $name, $notes, $gender, $species_id, $maturity) {
    global $dbconn;
    $sql = "INSERT INTO animals (user_id, nickname, notes, gender, species_id, maturity) 
            VALUES(:uid, :name, :notes, :gender, :sid, :m)";
    $stmt = $dbconn->prepare($sql);
    $stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':notes', $notes, PDO::PARAM_STR);
    $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
    $stmt->bindParam(':sid', $species_id, PDO::PARAM_INT);
    $stmt->bindParam(':m', $maturity, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        return true;
    }
    return false; 
}

// View animals
function db_viewAnimals ($id, $type_name) {
    global $dbconn;
    $sql = "SELECT distinct species.name
    FROM species
    JOIN animal_type ON species.animal_type_id = animal_type.animal_type_id
    JOIN animals ON species.species_id = animals.species_id WHERE animals.user_id = :id AND animal_type.type_name = :tn";
    $stmt = $dbconn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':tn', $type_name, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return false;
}

// View species
function db_viewSpecies ($user_id, $animal_type_id) {
    global $dbconn;
    $sql = "SELECT * FROM species WHERE user_id = :uid AND animal_type_id = :aid";
    $stmt = $dbconn->prepare($sql);
    $stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':aid', $animal_type_id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return false;
}

// Get coordinates
function db_viewAnimalPerCoordinates ($user_id, $animal_id) {
    global $dbconn;
    $sql = "SELECT first_seen_long, first_seen_lat FROM animals WHERE animal_id = :aid AND user_id = :uid";
    $stmt = $dbconn->prepare($sql);
    $stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':aid', $animal_id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return false;
}

// Get species ID
function db_getSpeciesID ($user_id, $species) {
    global $dbconn;
    $sql = "SELECT species_id FROM species WHERE name = :s AND user_id = :uid";
    $stmt = $dbconn->prepare($sql);
    $stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':s', $species, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return false;
}

// View one animal
function db_viewOneAnimal ($user_id, $animal_id) {
    global $dbconn;
    $sql = "SELECT * FROM animals INNER JOIN logs on animals.animal_id = logs.animal_id WHERE animals.user_id = :uid AND animals.animal_id = :aid";
    $stmt = $dbconn->prepare($sql);
    $stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':aid', $animal_id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return false;
}

// View animals per species
function db_viewAnimalsPerSpecies ($user_id, $species) {
    global $dbconn;
    $sql = "SELECT * FROM animals INNER JOIN species ON animals.species_id = species.species_id WHERE animals.user_id = :uid AND species.name = :s";
    $stmt = $dbconn->prepare($sql);
    $stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':s', $species, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return false;
}

// Entries count per species
function db_speciesCount ($user_id, $species) {
    global $dbconn;
    $sql = "SELECT COUNT(species.name)
    FROM species
    JOIN animals ON species.species_id = animals.species_id WHERE animals.user_id = :uid AND species.name = :s";
    $stmt = $dbconn->prepare($sql);
    $stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':s', $species, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return false;
}

// Edit animal
function db_editAnimal ($animal_id, $species_id, $user_id) {
    global $dbconn;
    $sql = "UPDATE animals SET species_id = :sid, user_id = :uid WHERE animal_id = :aid";
    $stmt = $dbconn->prepare($sql);
    $stmt->bindParam(':aid', $animal_id, PDO::PARAM_INT);
    $stmt->bindParam(':sid', $species_id, PDO::PARAM_INT);
    $stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        return true;
    }
    return false;
}

// Delete animal
function db_deleteAnimal ($animal_id) {
    global $dbconn;
    $sql = "DELETE FROM animals WHERE animal_id = :aid";
    $stmt = $dbconn->prepare($sql);
    $stmt->bindParam(':aid', $animal_id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        return true;
    }
    return false;
}

// Add log
function db_addLog ($title, $text, $animal_id) {
    global $dbconn;
    $sql = "INSERT INTO logs (title, text, animal_id) 
            VALUES (:title, :text, :aid)";
    $stmt = $dbconn->prepare($sql);
    $stmt->bindParam(':title', $title, PDO::PARAM_STR);
    $stmt->bindParam(':text', $text, PDO::PARAM_STR);
    $stmt->bindParam(':aid', $animal_id, PDO::PARAM_INT);
    $stmt->execute();
    return true;
}

// Delete account
function db_deleteAccount($user_id) {
    global $dbconn;
    $sql = "DELETE FROM users WHERE user_id = :uid";
    $stmt = $dbconn->prepare($sql);
    $stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    return true;
}

// File upload
function db_addImage($image) {
    global $dbconn;
    $sql = "INSERT INTO image_test (image) VALUES (:i)";
    $stmt = $dbconn->prepare($sql);
    $stmt->bindParam(':i', $image, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        return true;
    }
    return false; 
}

// Check if admin
function db_isAdmin($email) {
    global $dbconn;
    $sql = "SELECT email FROM users WHERE email = :e AND role = 'admin'";
    $stmt = $dbconn->prepare($sql);
    $stmt->bindParam(':e', $email, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        return true;
    }
    return false; 
}

// For admin
// View all species
function db_viewAllSpecies($user_id) {
    global $dbconn;
    $sql = "SELECT * FROM species WHERE user_id = :uid";
    $stmt = $dbconn->prepare($sql);
    $stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    return false;
}

// Edit first name
function db_editFirstName ($user_id, $firstname) {
    global $dbconn;
    $sql = "UPDATE users SET first_name = :fn WHERE user_id = :uid";
    $stmt = $dbconn->prepare($sql);
    $stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':fn', $firstname, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() > 0) { 
        return true;
    }
    return false;
}

// Edit last name
function db_editLastName ($user_id, $lastname) {
    global $dbconn;
    $sql = "UPDATE users SET last_name = :ln WHERE user_id = :uid";
    $stmt = $dbconn->prepare($sql);
    $stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':ln', $lastname, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() > 0) { 
        return true;
    }
    return false;
}

// Edit email
function db_editEmail($user_id, $email) {
    global $dbconn;
    $sql = "UPDATE users SET email = :e WHERE user_id = :uid";
    $stmt = $dbconn->prepare($sql);
    $stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':e', $email, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() > 0) { 
        return true;
    }
    return false;
}

// Edit password
function db_editPassword ($user_id, $password) {
    global $dbconn;
    $sql = "UPDATE users SET pass= :p WHERE user_id = :uid";
    $stmt = $dbconn->prepare($sql);
    $stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
    $stmt->bindParam('p', $password, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() > 0) { 
        return true;
    }
    return false;
}

// View user details
function db_viewUserDetails ($user_id) {
    global $dbconn;
    $sql = "SELECT * FROM users WHERE user_id = :uid";
    $stmt = $dbconn->prepare($sql);
    $stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return false;
}

// View animal details
function db_viewAnimalDetails ($user_id, $animal_id) {
    global $dbconn;
    $sql = "SELECT animal_type.type_name, species.name, animals.nickname, animals.notes, animals.gender, animals. maturity, animals.image
    FROM animal_type
    JOIN species ON animal_type.animal_type_id = species.animal_type_id
    JOIN animals ON species.species_id = animals.species_id WHERE animals.animal_id = :aid AND animals.user_id = :uid";
    $stmt = $dbconn->prepare($sql);
    $stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':aid', $animal_id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return false;
}

// Edit animal name
function db_editAnimalName ($user_id, $name, $animal_id) {
    global $dbconn;
    $sql = "UPDATE animals SET nickname = :n WHERE user_id = :uid AND animal_id = :aid";
    $stmt = $dbconn->prepare($sql);
    $stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':n', $name, PDO::PARAM_STR);
    $stmt->bindParam(':aid', $animal_id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() > 0) { 
        return true;
    }
    return false;
}

// Edit animal gender
function db_editAnimalGender ($user_id, $gender, $animal_id) {
    global $dbconn;
    $sql = "UPDATE animals SET gender = :g WHERE user_id = :uid AND animal_id = :aid";
    $stmt = $dbconn->prepare($sql);
    $stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':g', $gender, PDO::PARAM_STR);
    $stmt->bindParam(':aid', $animal_id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() > 0) { 
        return true;
    }
    return false;
}

// Edit animal maturity
function db_editAnimalMaturity ($user_id, $maturity, $animal_id) {
    global $dbconn;
    $sql = "UPDATE animals SET maturity = :m WHERE user_id = :uid AND animal_id = :aid";
    $stmt = $dbconn->prepare($sql);
    $stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':m', $maturity, PDO::PARAM_STR);
    $stmt->bindParam(':aid', $animal_id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() > 0) { 
        return true;
    }
    return false;
}

// Edit animal notes
function db_editAnimalNotes ($user_id, $notes, $animal_id) {
    global $dbconn;
    $sql = "UPDATE animals SET notes = :n WHERE user_id = :uid AND animal_id = :aid";
    $stmt = $dbconn->prepare($sql);
    $stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':n', $notes, PDO::PARAM_STR);
    $stmt->bindParam(':aid', $animal_id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() > 0) { 
        return true;
    }
    return false;
}