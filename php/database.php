<?php 
include 'connect.php';

// -- Table structure for table `chat_messages`

$sql_chat = "CREATE TABLE IF NOT EXISTS `chat_messages` (
  `id_message` int(11) NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `sender_tutor_id` int(6) UNSIGNED DEFAULT NULL,
  `sender_provider_id` int(6) UNSIGNED DEFAULT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `receiver_tutor_id` int(6) UNSIGNED DEFAULT NULL,
  `receiver_provider_id` int(6) UNSIGNED DEFAULT NULL,
  `message` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";

if (mysqli_query($conn, $sql_chat)) {
    echo "Table 'chat_messages' created successfully.";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}


// -- Table structure for table `course`

$sql_course = " CREATE TABLE IF NOT EXISTS `course` (
  `id_course` int(6) UNSIGNED NOT NULL,
  `name_course` varchar(50) NOT NULL,
  `detail_course` varchar(255) NOT NULL,
  `date_course` varchar(30) NOT NULL,
  `date_course_end` varchar(30) NOT NULL,
  `day_course` varchar(30) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `duration_hour` varchar(50) NOT NULL,
  `price_course` int(30) NOT NULL,
  `meeting_type` varchar(30) NOT NULL,
  `room_course` varchar(30) NOT NULL,
  `image_course` varchar(255) NOT NULL,
  `id_tutor` int(6) UNSIGNED DEFAULT NULL,
  `flie_doc` varchar(255) DEFAULT NULL,
  `label_doc` varchar(50) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";

if (mysqli_query($conn, $sql_course)) {
    echo "Table 'course' created successfully.";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}

// -- Table structure for table `details_roombooking`

$sql_details_roombooking = " CREATE TABLE IF NOT EXISTS `details_roombooking` (
  `id_details` int(6) UNSIGNED NOT NULL,
  `id_room` int(6) UNSIGNED NOT NULL,
  `id_food` int(6) UNSIGNED DEFAULT NULL,
  `id_promotion_room` int(6) DEFAULT NULL,
  `total_pricess` float NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id` int(11) DEFAULT NULL,
  `id_tutor` int(6) UNSIGNED DEFAULT NULL,
  `id_bookingroom` int(6) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci; ";

if (mysqli_query($conn, $sql_details_roombooking)) {
    echo "Table 'details_roombooking' created successfully.";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}

// -- Table structure for table `food`

$sql_food = " CREATE TABLE IF NOT EXISTS `food` (
  `id_food` int(6) UNSIGNED NOT NULL,
  `name_food` varchar(50) NOT NULL,
  `detail_food` varchar(255) NOT NULL,
  `type_food` varchar(30) NOT NULL,
  `price_food` int(7) NOT NULL,
  `image_food` varchar(255) NOT NULL,
  `id_provider` int(6) UNSIGNED NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci; ";

if (mysqli_query($conn, $sql_food)) {
    echo "Table 'food' created successfully.";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}

// -- Table structure for table `meetingroom`

$sql_meetingroom = " CREATE TABLE IF NOT EXISTS `meetingroom` (
  `id_provider` int(6) UNSIGNED DEFAULT NULL,
  `id_room` int(6) UNSIGNED NOT NULL,
  `name_room` varchar(50) NOT NULL,
  `detail_room` varchar(255) NOT NULL,
  `opacity_room` varchar(20) NOT NULL,
  `price_room` varchar(30) NOT NULL,
  `room_type` varchar(50) NOT NULL,
  `number_room` varchar(10) NOT NULL,
  `image_room` varchar(255) NOT NULL,
  `address_room` varchar(255) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci; ";

if (mysqli_query($conn, $sql_meetingroom)) {
    echo "Table 'meetingroom' created successfully.";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}


// -- Table structure for table `notifications_course`

$sql_notifications_course = " CREATE TABLE IF NOT EXISTS `notifications_course` (
  `id_noti_course` int(6) UNSIGNED NOT NULL,
  `message_c` varchar(255) NOT NULL,
  `status_noti_course` enum('read','unread') DEFAULT 'unread',
  `id_register_course` int(6) UNSIGNED NOT NULL,
  `time_noti_course` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";

if (mysqli_query($conn, $sql_notifications_course)) {
    echo "Table 'notifications_course' created successfully.";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}


// -- Table structure for table `notifications_room`

$sql_notifications_room = " CREATE TABLE IF NOT EXISTS `notifications_room` (
  `id_noti_room` int(6) UNSIGNED NOT NULL,
  `id_sum_room` int(6) UNSIGNED DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `status` enum('read','unread') DEFAULT 'unread',
  `notification_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci; ";

if (mysqli_query($conn, $sql_notifications_room)) {
    echo "Table 'notifications_room' created successfully.";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}

// -- Table structure for table `payment_room`

$sql_payment_room = " CREATE TABLE IF NOT EXISTS `payment_room` (
  `id_paymentroom` int(6) UNSIGNED NOT NULL,
  `id_bookingroom` int(6) UNSIGNED NOT NULL,
  `total_pricess` float NOT NULL,
  `totalAmount` decimal(10,2) NOT NULL,
  `paymentOption` enum('มัดจำ 50%','ชำระเต็มจำนวน') NOT NULL,
  `paymentMethod` varchar(50) DEFAULT NULL,
  `status` enum('รอดำเนินการ','มัดจำแล้ว','ชำระเงินครบแล้ว','ยกเลิก') DEFAULT 'รอดำเนินการ',
  `qrCodeUpload` varchar(255) DEFAULT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci; ";

if (mysqli_query($conn, $sql_payment_room)) {
    echo "Table 'payment_room' created successfully.";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}


// -- Table structure for table `promotion_room`

$sql_promotion_room = " CREATE TABLE IF NOT EXISTS `promotion_room` (
  `id_promotion_room` int(6) NOT NULL,
  `promotion_title_room` varchar(255) NOT NULL,
  `promotion_type_room` varchar(50) NOT NULL,
  `discount_value_room` varchar(50) NOT NULL,
  `start_date_room` date NOT NULL,
  `end_date_room` date NOT NULL,
  `promo_code_room` varchar(100) DEFAULT NULL,
  `terms` text NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_room` int(6) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci; ";

if (mysqli_query($conn, $sql_promotion_room)) {
    echo "Table 'promotion_room' created successfully.";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}

// -- Table structure for table `provider`

$sql_provider = " CREATE TABLE IF NOT EXISTS `provider` (
  `id_provider` int(6) UNSIGNED NOT NULL,
  `name_provider` varchar(100) NOT NULL,
  `number_provider` varchar(10) NOT NULL,
  `address_provider` varchar(255) NOT NULL,
  `email_provider` varchar(50) NOT NULL,
  `password_provider` varchar(50) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";

if (mysqli_query($conn, $sql_provider)) {
    echo "Table 'provider' created successfully.";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}


// -- Table structure for table `register_course`

$sql_register_course = " CREATE TABLE IF NOT EXISTS `register_course` (
  `id_register_course` int(6) UNSIGNED NOT NULL,
  `name_std` varchar(50) NOT NULL,
  `email_std` varchar(50) NOT NULL,
  `number_std` varchar(20) NOT NULL,
  `status_register` enum('รอดำเนินการ','ชำระแล้ว','ชำระไม่สำเร็จ','ยกเลิก') DEFAULT 'รอดำเนินการ',
  `qrCodeUpload_course` varchar(255) NOT NULL,
  `id_course` int(6) UNSIGNED NOT NULL,
  `id` int(11) DEFAULT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci; ";

if (mysqli_query($conn, $sql_register_course)) {
    echo "Table 'register_course' created successfully.";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}


// -- Table structure for table `review_course`

$sql_review_course = " CREATE TABLE IF NOT EXISTS `review_course` (
  `id_review_course` int(6) NOT NULL,
  `id_register_course` int(6) UNSIGNED NOT NULL,
  `id` int(11) NOT NULL,
  `id_course` int(6) UNSIGNED NOT NULL,
  `review_course` text DEFAULT NULL,
  `rating_course` int(11) DEFAULT NULL CHECK (`rating_course` >= 1 and `rating_course` <= 5),
  `review_date_course` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";

if (mysqli_query($conn, $sql_review_course)) {
    echo "Table 'review_course' created successfully.";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}

// -- Table structure for table `review_room`

$sql_review_room = " CREATE TABLE IF NOT EXISTS `review_room` (
  `id_review_room` int(6) UNSIGNED NOT NULL,
  `id_bookingroom` int(6) UNSIGNED NOT NULL,
  `id` int(11) DEFAULT NULL,
  `id_tutor` int(6) UNSIGNED DEFAULT NULL,
  `id_room` int(6) UNSIGNED NOT NULL,
  `review_room` text DEFAULT NULL,
  `rating_room` int(11) DEFAULT NULL CHECK (`rating_room` >= 1 and `rating_room` <= 5),
  `review_date_room` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";

if (mysqli_query($conn, $sql_review_room)) {
    echo "Table 'review_room' created successfully.";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}

// -- Table structure for table `roombooking`

$sql_roombooking = " CREATE TABLE IF NOT EXISTS `roombooking` (
  `id_bookingroom` int(6) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `day_book` int(100) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";

if (mysqli_query($conn, $sql_roombooking)) {
    echo "Table 'roombooking' created successfully.";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}


// -- Table structure for table `room_booking_summary`

$sql_room_booking_summary = " CREATE TABLE IF NOT EXISTS `room_booking_summary` (
  `id_sum_room` int(6) UNSIGNED NOT NULL,
  `id_bookingroom` int(6) UNSIGNED DEFAULT NULL,
  `id_details` int(6) UNSIGNED DEFAULT NULL,
  `id_paymentroom` int(6) UNSIGNED DEFAULT NULL,
  `status_summary` enum('active','cancelled') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";

if (mysqli_query($conn, $sql_room_booking_summary)) {
    echo "Table 'room_booking_summary' created successfully.";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}

// -- Table structure for table `tutor`

$sql_tutor = " CREATE TABLE IF NOT EXISTS `tutor` (
  `id_tutor` int(6) UNSIGNED NOT NULL,
  `name_lastname_tutor` varchar(100) NOT NULL,
  `gender_tutor` varchar(20) NOT NULL,
  `address_tutor` varchar(255) NOT NULL,
  `email_tutor` varchar(100) NOT NULL,
  `password_tutor` varchar(100) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci; ";

if (mysqli_query($conn, $sql_tutor)) {
    echo "Table 'tutor' created successfully.";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}

// -- Table structure for table `user`

$sql_user = " CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL,
  `name_lastname` varchar(100) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `status` varchar(30) NOT NULL,
  `address` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `confirm_password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci; ";

if (mysqli_query($conn, $sql_user)) {
    echo "Table 'user' created successfully.";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}


// === ฟังก์ชันเช็ก index หรือ primary key ซ้ำ ===
function indexExists($conn, $table, $key) {
    $result = mysqli_query($conn, "SHOW INDEX FROM `$table` WHERE Key_name = '$key'");
    return mysqli_num_rows($result) > 0;
}

// === รายการ index ทั้งหมด พร้อมชื่อ key และตาราง ===
$indexQueries = [
    // 🔹 chat_messages
    ['table' => 'chat_messages', 'key' => 'PRIMARY', 'sql' => "ALTER TABLE chat_messages ADD PRIMARY KEY (id_message)"],
    ['table' => 'chat_messages', 'key' => 'fk_receiver_general', 'sql' => "ALTER TABLE chat_messages ADD KEY fk_receiver_general (receiver_id)"],
    ['table' => 'chat_messages', 'key' => 'fk_receiver_provider', 'sql' => "ALTER TABLE chat_messages ADD KEY fk_receiver_provider (receiver_provider_id)"],
    ['table' => 'chat_messages', 'key' => 'fk_receiver_tutor', 'sql' => "ALTER TABLE chat_messages ADD KEY fk_receiver_tutor (receiver_tutor_id)"],
    ['table' => 'chat_messages', 'key' => 'fk_sender_general', 'sql' => "ALTER TABLE chat_messages ADD KEY fk_sender_general (sender_id)"],
    ['table' => 'chat_messages', 'key' => 'fk_sender_provider', 'sql' => "ALTER TABLE chat_messages ADD KEY fk_sender_provider (sender_provider_id)"],
    ['table' => 'chat_messages', 'key' => 'fk_sender_tutor', 'sql' => "ALTER TABLE chat_messages ADD KEY fk_sender_tutor (sender_tutor_id)"],

    // 🔹 course
    ['table' => 'course', 'key' => 'PRIMARY', 'sql' => "ALTER TABLE course ADD PRIMARY KEY (id_course)"],
    ['table' => 'course', 'key' => 'id_tutor', 'sql' => "ALTER TABLE course ADD KEY id_tutor (id_tutor)"],

    // 🔹 details_roombooking
    ['table' => 'details_roombooking', 'key' => 'PRIMARY', 'sql' => "ALTER TABLE details_roombooking ADD PRIMARY KEY (id_details)"],
    ['table' => 'details_roombooking', 'key' => 'id_room', 'sql' => "ALTER TABLE details_roombooking ADD KEY id_room (id_room)"],
    ['table' => 'details_roombooking', 'key' => 'id_food', 'sql' => "ALTER TABLE details_roombooking ADD KEY id_food (id_food)"],
    ['table' => 'details_roombooking', 'key' => 'id_promotion_room', 'sql' => "ALTER TABLE details_roombooking ADD KEY id_promotion_room (id_promotion_room)"],
    ['table' => 'details_roombooking', 'key' => 'id', 'sql' => "ALTER TABLE details_roombooking ADD KEY id (id)"],
    ['table' => 'details_roombooking', 'key' => 'id_tutor', 'sql' => "ALTER TABLE details_roombooking ADD KEY id_tutor (id_tutor)"],
    ['table' => 'details_roombooking', 'key' => 'id_bookingroom', 'sql' => "ALTER TABLE details_roombooking ADD KEY id_bookingroom (id_bookingroom)"],

    // 🔹 food
    ['table' => 'food', 'key' => 'PRIMARY', 'sql' => "ALTER TABLE food ADD PRIMARY KEY (id_food)"],
    ['table' => 'food', 'key' => 'id_provider', 'sql' => "ALTER TABLE food ADD KEY id_provider (id_provider)"],

    // 🔹 meetingroom
    ['table' => 'meetingroom', 'key' => 'PRIMARY', 'sql' => "ALTER TABLE meetingroom ADD PRIMARY KEY (id_room)"],
    ['table' => 'meetingroom', 'key' => 'id_provider', 'sql' => "ALTER TABLE meetingroom ADD KEY id_provider (id_provider)"],

    // 🔹 notifications_course
    ['table' => 'notifications_course', 'key' => 'PRIMARY', 'sql' => "ALTER TABLE notifications_course ADD PRIMARY KEY (id_noti_course)"],
    ['table' => 'notifications_course', 'key' => 'id_register_course', 'sql' => "ALTER TABLE notifications_course ADD KEY id_register_course (id_register_course)"],

    // 🔹 notifications_room
    ['table' => 'notifications_room', 'key' => 'PRIMARY', 'sql' => "ALTER TABLE notifications_room ADD PRIMARY KEY (id_noti_room)"],
    ['table' => 'notifications_room', 'key' => 'id_sum_room', 'sql' => "ALTER TABLE notifications_room ADD KEY id_sum_room (id_sum_room)"],

    // 🔹 payment_room
    ['table' => 'payment_room', 'key' => 'PRIMARY', 'sql' => "ALTER TABLE payment_room ADD PRIMARY KEY (id_paymentroom)"],
    ['table' => 'payment_room', 'key' => 'id_bookingroom', 'sql' => "ALTER TABLE payment_room ADD KEY id_bookingroom (id_bookingroom)"],

    // 🔹 promotion_room
    ['table' => 'promotion_room', 'key' => 'PRIMARY', 'sql' => "ALTER TABLE promotion_room ADD PRIMARY KEY (id_promotion_room)"],
    ['table' => 'promotion_room', 'key' => 'id_room', 'sql' => "ALTER TABLE promotion_room ADD KEY id_room (id_room)"],

    // 🔹 provider
    ['table' => 'provider', 'key' => 'PRIMARY', 'sql' => "ALTER TABLE provider ADD PRIMARY KEY (id_provider)"],

    // 🔹 register_course
    ['table' => 'register_course', 'key' => 'PRIMARY', 'sql' => "ALTER TABLE register_course ADD PRIMARY KEY (id_register_course)"],
    ['table' => 'register_course', 'key' => 'id_course', 'sql' => "ALTER TABLE register_course ADD KEY id_course (id_course)"],
    ['table' => 'register_course', 'key' => 'id', 'sql' => "ALTER TABLE register_course ADD KEY id (id)"],

    // 🔹 review_course
    ['table' => 'review_course', 'key' => 'PRIMARY', 'sql' => "ALTER TABLE review_course ADD PRIMARY KEY (id_review_course)"],
    ['table' => 'review_course', 'key' => 'id_register_course', 'sql' => "ALTER TABLE review_course ADD KEY id_register_course (id_register_course)"],
    ['table' => 'review_course', 'key' => 'id', 'sql' => "ALTER TABLE review_course ADD KEY id (id)"],
    ['table' => 'review_course', 'key' => 'id_course', 'sql' => "ALTER TABLE review_course ADD KEY id_course (id_course)"],

    // 🔹 review_room
    ['table' => 'review_room', 'key' => 'PRIMARY', 'sql' => "ALTER TABLE review_room ADD PRIMARY KEY (id_review_room)"],
    ['table' => 'review_room', 'key' => 'id_bookingroom', 'sql' => "ALTER TABLE review_room ADD KEY id_bookingroom (id_bookingroom)"],
    ['table' => 'review_room', 'key' => 'id', 'sql' => "ALTER TABLE review_room ADD KEY id (id)"],
    ['table' => 'review_room', 'key' => 'id_room', 'sql' => "ALTER TABLE review_room ADD KEY id_room (id_room)"],
    ['table' => 'review_room', 'key' => 'id_tutor', 'sql' => "ALTER TABLE review_room ADD KEY id_tutor (id_tutor)"],

    // 🔹 roombooking
    ['table' => 'roombooking', 'key' => 'PRIMARY', 'sql' => "ALTER TABLE roombooking ADD PRIMARY KEY (id_bookingroom)"],

    // 🔹 room_booking_summary
    ['table' => 'room_booking_summary', 'key' => 'PRIMARY', 'sql' => "ALTER TABLE room_booking_summary ADD PRIMARY KEY (id_sum_room)"],
    ['table' => 'room_booking_summary', 'key' => 'id_bookingroom', 'sql' => "ALTER TABLE room_booking_summary ADD KEY id_bookingroom (id_bookingroom)"],
    ['table' => 'room_booking_summary', 'key' => 'id_details', 'sql' => "ALTER TABLE room_booking_summary ADD KEY id_details (id_details)"],
    ['table' => 'room_booking_summary', 'key' => 'id_paymentroom', 'sql' => "ALTER TABLE room_booking_summary ADD KEY id_paymentroom (id_paymentroom)"],

    // 🔹 tutor
    ['table' => 'tutor', 'key' => 'PRIMARY', 'sql' => "ALTER TABLE tutor ADD PRIMARY KEY (id_tutor)"],

    // 🔹 user
    ['table' => 'user', 'key' => 'PRIMARY', 'sql' => "ALTER TABLE user ADD PRIMARY KEY (id)"]
];

// === รันคำสั่ง Index ทีละคำสั่ง พร้อมเช็กก่อน ===
foreach ($indexQueries as $index) {
    if (!indexExists($conn, $index['table'], $index['key'])) {
        if (!mysqli_query($conn, $index['sql'])) {
            echo "❌ Index Error ({$index['table']}.{$index['key']}): " . mysqli_error($conn) . "<br>\n";
        } else {
            echo "✅ Index '{$index['key']}' added to '{$index['table']}'<br>\n";
        }
    } else {
        echo "ℹ️ Index '{$index['key']}' already exists on '{$index['table']}'<br>\n";
    }
}

mysqli_close($conn);
?>
