-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 10, 2025 at 01:33 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `safedrive_insurance`
--

-- --------------------------------------------------------

--
-- Table structure for table `additionaldriver`
--

CREATE TABLE `additionaldriver` (
  `Driver_ID` int(10) NOT NULL,
  `Driver_NRIC` bigint(20) UNSIGNED DEFAULT NULL,
  `Driver_Occupation` varchar(20) DEFAULT NULL,
  `Driver_RelationshipToApplicant` varchar(20) DEFAULT NULL,
  `Driver_DrivingExperienceYear` int(2) DEFAULT NULL,
  `Driver_TypeOfDrivingLicense` varchar(4) DEFAULT NULL,
  `Vehicle_RegNum` varchar(12) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `additionaldriver`
--

INSERT INTO `additionaldriver` (`Driver_ID`, `Driver_NRIC`, `Driver_Occupation`, `Driver_RelationshipToApplicant`, `Driver_DrivingExperienceYear`, `Driver_TypeOfDrivingLicense`, `Vehicle_RegNum`) VALUES
(107, 700317169216, 'Retired', 'Parent', 35, 'Full', 'PGP82'),
(125, 920717065432, 'Driver', 'Employee', 20, 'Full', 'WB223X'),
(201, 941231161384, 'Teacher', 'Sibling', 10, 'Full', 'BSA6017'),
(260, 730902050679, 'Driver', 'Worker', 30, 'Full', 'WB223X'),
(273, 950304073467, 'Doctor', 'Child', 1, 'P', 'PRL2243'),
(298, 850223078912, 'Teacher', 'Spouse', 15, 'Full', 'ERT7865'),
(350, 950305123456, 'Student', 'Child', 3, 'P', 'PRL2243'),
(400, 880618045678, 'Accountant', 'Sibling', 7, 'Full', 'QAA1342'),
(452, 910123456789, 'Engineer', 'Friend', 10, 'Full', 'BSA6017'),
(489, 990909040344, 'Accountant', 'Sibling', 5, 'Full', 'QAA1342');

-- --------------------------------------------------------

--
-- Table structure for table `application`
--

CREATE TABLE `application` (
  `Application_ID` int(10) NOT NULL,
  `Application_Date` date DEFAULT NULL,
  `Application_Status` varchar(7) DEFAULT NULL,
  `Policy_ID` int(10) DEFAULT NULL,
  `Vehicle_RegNum` varchar(12) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application`
--

INSERT INTO `application` (`Application_ID`, `Application_Date`, `Application_Status`, `Policy_ID`, `Vehicle_RegNum`) VALUES
(103, '2010-02-01', 'Approve', 1005, 'PGP82'),
(123, '2025-01-10', 'pending', 1012, '876543HGFDSA'),
(191, '2015-06-17', 'Approve', 1001, 'BSA6017'),
(234, '2019-03-25', 'Approve', 1004, 'WB223X'),
(263, '2020-07-08', 'Approve', 1003, 'PRL2243'),
(320, '2023-12-10', 'Approve', 1002, 'QAA1342'),
(350, '2023-11-01', 'Approve', 1006, 'JKL3219'),
(351, '2023-12-15', 'Approve', 1007, 'ERT7865'),
(368, '2024-01-10', 'Approve', 1008, 'POI5567'),
(402, '2024-02-05', 'Approve', 1009, 'MKA3298'),
(405, '2024-03-01', 'Approve', 1010, 'LYU8901');

-- --------------------------------------------------------

--
-- Table structure for table `bill`
--

CREATE TABLE `bill` (
  `Bill_ID` int(10) NOT NULL,
  `Transaction_Date` date NOT NULL,
  `Payment_Method` varchar(15) NOT NULL,
  `Payment_Amount` float(7,2) NOT NULL,
  `Policy_ID` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bill`
--

INSERT INTO `bill` (`Bill_ID`, `Transaction_Date`, `Payment_Method`, `Payment_Amount`, `Policy_ID`) VALUES
(1, '2024-01-09', 'online banking', 191.25, 1001),
(2, '2024-01-10', 'credit card', 266.67, 1002),
(3, '2024-01-15', 'cash', 258.33, 1004),
(4, '2024-02-02', 'credit card', 361.67, 1003),
(5, '2024-02-19', 'online banking', 183.33, 1005),
(6, '2024-03-12', 'credit card', 266.67, 1002),
(7, '2024-04-03', 'credit card', 382.50, 1001),
(8, '2024-04-10', 'credit card', 180.83, 1003),
(9, '2024-05-06', 'online banking', 516.67, 1004),
(10, '2024-06-01', 'credit card', 366.67, 1005),
(11, '2024-07-01', 'online banking', 200.50, 1006),
(12, '2024-07-15', 'credit card', 175.75, 1007),
(13, '2024-08-03', 'cash', 320.40, 1008),
(14, '2024-08-20', 'credit card', 250.90, 1009),
(15, '2024-09-01', 'online banking', 410.00, 1010);

-- --------------------------------------------------------

--
-- Table structure for table `claim`
--

CREATE TABLE `claim` (
  `Claim_ID` int(10) NOT NULL,
  `Claim_Reason` text DEFAULT NULL,
  `Claim_Amount` decimal(7,2) DEFAULT NULL,
  `Claim_Status` varchar(7) DEFAULT NULL,
  `Claim_Date` date DEFAULT NULL,
  `Application_ID` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `claim`
--

INSERT INTO `claim` (`Claim_ID`, `Claim_Reason`, `Claim_Amount`, `Claim_Status`, `Claim_Date`, `Application_ID`) VALUES
(200, 'Accident', 1200.00, 'Approve', '2015-02-03', 103),
(432, 'Accident', 5000.00, 'Approve', '2022-08-06', 191),
(1236, 'Accident', 3200.00, 'Approve', '2024-01-15', 350),
(1672, 'Stolen', 15000.00, 'Reject', '2024-02-03', 320),
(2321, 'Theft', 7500.00, 'Pending', '2024-02-20', 351),
(2982, 'Accident', 2500.00, 'Approve', '2024-05-08', 263),
(3829, 'Accident', 2500.00, 'Approve', '2024-03-01', 402),
(4938, 'Vandalised', 3000.00, 'Reject', '2024-04-10', 405),
(5426, 'Accident', 4100.00, 'Approve', '2024-05-05', 350),
(5673, 'Vandalised', 5000.00, 'Pending', '2024-10-15', 234),
(8734, 'Accident', 7000.00, 'Reject', '2024-03-05', 103);

-- --------------------------------------------------------

--
-- Table structure for table `claim_history`
--

CREATE TABLE `claim_history` (
  `ClaimHistory_ID` int(11) NOT NULL,
  `Claim_ID` int(10) NOT NULL,
  `ClaimHistory_Reason` varchar(15) DEFAULT NULL,
  `ClaimHistory_Amount` decimal(9,2) DEFAULT NULL,
  `Claim_Date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `claim_history`
--

INSERT INTO `claim_history` (`ClaimHistory_ID`, `Claim_ID`, `ClaimHistory_Reason`, `ClaimHistory_Amount`, `Claim_Date`) VALUES
(1, 200, 'Accident', 1200.00, '2015-02-03'),
(2, 432, 'Accident', 5000.00, '2022-08-06'),
(3, 1672, 'Stolen', 15000.00, '2024-02-03'),
(4, 2982, 'Accident', 2500.00, '2024-05-08'),
(5, 5673, 'Vadalised', 5000.00, '2024-10-15'),
(6, 8734, 'Accident', 7000.00, '2024-03-05'),
(12, 1236, 'Accident', 3200.00, '2024-01-15'),
(13, 2321, 'Theft', 7500.00, '2024-02-20'),
(14, 3829, 'Accident', 2500.00, '2024-03-01'),
(15, 4938, 'Vandalised', 3000.00, '2024-04-10');

-- --------------------------------------------------------

--
-- Table structure for table `commercial`
--

CREATE TABLE `commercial` (
  `Policy_ID` int(6) NOT NULL,
  `CompRegNum` int(10) NOT NULL,
  `CompName` varchar(30) NOT NULL,
  `CompRegDate` date NOT NULL,
  `CompAddress` text NOT NULL,
  `CompPhoneNum` bigint(20) NOT NULL,
  `CompEmailAddress` text NOT NULL,
  `CompBankName` varchar(30) NOT NULL,
  `CompBankAccNum` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `commercial`
--

INSERT INTO `commercial` (`Policy_ID`, `CompRegNum`, `CompName`, `CompRegDate`, `CompAddress`, `CompPhoneNum`, `CompEmailAddress`, `CompBankName`, `CompBankAccNum`) VALUES
(1001, 2001123445, 'ABC Dental Clinic', '2001-10-10', 'First floor, No. 10A-2, Jalan 14/20, 46100 Petaling Jaya, Selangor', 60112345678, 'ABCDental@gmail.com', 'Maybank', 514137215055),
(1004, 1999123456, 'Angelina Enterprise', '1999-01-01', '30, Jalan 1, Ampang Jaya, 68000 Ampang, Selangor', 60124567893, 'AngelinaEnprise@gmail.com', 'Public Bank', 3193179313),
(1006, 2010567890, 'Tech Innovators Sdn Bhd', '2010-03-25', 'Jalan Teknologi 1, Kuala Lumpur', 60312345678, 'contact@techinnovators.com', 'CitiBank', 112233445566),
(1009, 2015483647, 'Green Solutions Sdn Bhd', '2015-07-14', 'No. 45, Jalan Hijau, Penang', 60498765432, 'info@greensolutions.com', 'Maybank', 223344556677),
(1010, 2018483647, 'Innovative Ideas Sdn Bhd', '2018-11-05', 'Menara Tech, Cyberjaya, Selangor', 60387654321, 'support@innovativeideas.com', 'Public Bank', 334455667788);

-- --------------------------------------------------------

--
-- Table structure for table `driver_details`
--

CREATE TABLE `driver_details` (
  `Driver_FirstName` varchar(30) DEFAULT NULL,
  `Driver_LastName` varchar(30) DEFAULT NULL,
  `Driver_Gender` varchar(1) DEFAULT NULL,
  `Driver_NRIC` bigint(20) UNSIGNED NOT NULL,
  `Driver_DOB` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `driver_details`
--

INSERT INTO `driver_details` (`Driver_FirstName`, `Driver_LastName`, `Driver_Gender`, `Driver_NRIC`, `Driver_DOB`) VALUES
('wqefrgt', 'waesrdfg', 'F', 98765432345, '2025-01-10'),
('Jessica', 'Smith', 'F', 700317169216, '1970-03-17'),
('Phang Soo', 'Lee', 'M', 730902050679, '1973-09-02'),
('Mei', 'Tan', 'F', 850223078912, '1985-08-22'),
('Tan', 'Heng', 'M', 880618045678, '1990-11-25'),
('Ahmad', 'Zainuddin', 'M', 910123456789, '1992-05-14'),
('Muthu', 'Krishnan', 'M', 920717065432, '1983-02-10'),
('Swathi', 'Vasuthevan', 'F', 941231161384, '1994-12-31'),
('Akmal Fikri', 'Bin Muhamad Abu Bakar', 'M', 950304073467, '1995-03-04'),
('Siti', 'Rahman', 'F', 950305123456, '2000-09-12'),
('Wai Koon', 'Wong', 'M', 990909040344, '1999-09-09');

-- --------------------------------------------------------

--
-- Table structure for table `personal`
--

CREATE TABLE `personal` (
  `Policy_ID` int(10) NOT NULL,
  `P_BankAccName` varchar(30) DEFAULT NULL,
  `P_BankAccNum` bigint(20) DEFAULT NULL,
  `P_Occupation` varchar(30) DEFAULT NULL,
  `P_NumOfDrivers` int(2) DEFAULT NULL,
  `P_Usage_Details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `personal`
--

INSERT INTO `personal` (`Policy_ID`, `P_BankAccName`, `P_BankAccNum`, `P_Occupation`, `P_NumOfDrivers`, `P_Usage_Details`) VALUES
(1002, 'Maybank', 551025210571, 'Teacher', 3, 'Open Public Car Park, Air Brake System (ABS)'),
(1003, 'CIMB', 8007228714, 'Pharmacist', 3, 'Locked Compound, Anti-Theft device installed, Air Brake System (ABS)'),
(1005, 'Maybank', 151584031980, 'Baker', 2, 'Secure Public Car Park, The vehicle was modified'),
(1007, 'RHB Bank', 922134567890, 'Software Developer', 1, 'Covered parking, Anti-theft alarm, GPS Tracking'),
(1008, 'Public Bank', 902345678901, 'Engineer', 1, 'Garage parking, Anti-theft system, Automatic transmission');

-- --------------------------------------------------------

--
-- Table structure for table `policy`
--

CREATE TABLE `policy` (
  `Policy_ID` int(10) NOT NULL,
  `Info_ID` int(3) DEFAULT NULL,
  `Policy_Start` date DEFAULT NULL,
  `Policy_END` date DEFAULT NULL,
  `Policy_Type` varchar(14) NOT NULL,
  `Policy_Purpose` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `policy`
--

INSERT INTO `policy` (`Policy_ID`, `Info_ID`, `Policy_Start`, `Policy_END`, `Policy_Type`, `Policy_Purpose`) VALUES
(1001, 1, '2015-07-01', '2090-07-01', 'Comprehensive', 'Commercial'),
(1002, 2, '2023-12-15', '2098-12-15', 'Comprehensive', 'Personal'),
(1003, 3, '2020-07-10', '2095-07-10', 'Comprehensive', 'Personal'),
(1004, 4, '2019-04-05', '2094-04-05', 'Comprehensive', 'Commercial'),
(1005, 5, '2010-02-08', '2085-02-08', 'Third Party', 'Personal'),
(1006, 6, '2021-05-15', '2096-05-15', 'Comprehensive', 'Commercial'),
(1007, 7, '2022-11-01', '2097-11-01', 'Third Party', 'Personal'),
(1008, 8, '2023-01-01', '2098-01-01', 'Comprehensive', 'Personal'),
(1009, 9, '2024-06-10', '2099-06-10', 'Third Party', 'Commercial'),
(1010, 10, '2024-03-20', '2099-03-20', 'Comprehensive', 'Commercial'),
(1012, 65, '2025-01-10', '2025-01-16', 'Third Party', 'Commercial');

-- --------------------------------------------------------

--
-- Table structure for table `policyholder`
--

CREATE TABLE `policyholder` (
  `PolicyHolder_ID` int(10) NOT NULL,
  `PolicyHolder_NRIC` bigint(20) UNSIGNED DEFAULT NULL,
  `PolicyHolder_MaritalStatus` varchar(10) DEFAULT NULL,
  `PolicyHolder_Occupation` varchar(30) DEFAULT NULL,
  `PolicyHolder_PhoneNum` bigint(20) UNSIGNED DEFAULT NULL,
  `PolicyHolder_Email` text DEFAULT NULL,
  `PolicyHolder_Address` text DEFAULT NULL,
  `PolicyHolder_LicenseNum` varchar(20) DEFAULT NULL,
  `PolicyHolder_AmountDue` decimal(8,2) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `policyholder`
--

INSERT INTO `policyholder` (`PolicyHolder_ID`, `PolicyHolder_NRIC`, `PolicyHolder_MaritalStatus`, `PolicyHolder_Occupation`, `PolicyHolder_PhoneNum`, `PolicyHolder_Email`, `PolicyHolder_Address`, `PolicyHolder_LicenseNum`, `PolicyHolder_AmountDue`) VALUES
(1, 971018020561, 'Married', 'Dentist', 60112345678, 'tham1997@yahoo.com', '2,Jalan SS 2/78, SS 2, 47300 Petaling Jaya, Selangor', '0105491 VhpLySDy', 0.00),
(2, 990909040344, 'Single', 'Teacher', 60123456789, 'whl1002@gmail.com', '12, Lorong Unigarden 8B2, 94300 Kota Samarahan, Sarawak', '0104031 RwrLyZBx', 500.20),
(3, 750208080189, 'Married', 'Pharmacist', 60187654321, 'muhamad@gmail.com', '15 Apartment, 9A Jalan Van Praagh, Taman Continental, 11600 Jelutong,Penang', '0110051 U3DVajEe', 205.67),
(4, 860521060302, 'Married', 'Secretary', 60124567893, 'angelina1986@gmail.com', 'Lot 27, Jalan Mamanda 4, Ampang Point, Taman Dato Ahmad Razali, 68000 Ampang, Selangor', '0108451 KbhDUiPd', 632.00),
(5, 901104021123, 'Single', 'Baker', 60134567891, 'js1990@gmail.com', 'No 15 Tingkat 1-7, (Podium) Persiaran Perdana, Presint 2, 62550 Putrajaya', '0108278 CysRZbje', 1410.00),
(6, 900123045678, 'Single', 'Engineer', 60122345678, 'ahmad.nor@example.my', 'No. 12, Jalan Ampang, Kuala Lumpur', 'P1234567', 125.00),
(7, 880223075612, 'Married', 'Teacher', 60173456789, 'lim.mei@example.my', 'No. 8, Taman Bukit Indah, Johor Bahru', 'J2345678', 320.75),
(8, 950305145634, 'Single', 'Doctor', 60191234567, 'siti.rahman@example.my', 'No. 5, Kampung Pandan, Kuala Lumpur', 'K3456789', 0.00),
(9, 850618035679, 'Married', 'Accountant', 60134567890, 'tan.kim@example.my', 'No. 10, Jalan Gasing, Petaling Jaya', 'L9876543', 80.00),
(10, 920717085611, 'Divorced', 'Lawyer', 60145678901, 'muthu.krishnan@example.my', 'No. 22, Jalan Tun Razak, Ipoh', 'M8765432', 450.50),
(11, 123456789056, 'Married', 'Dentistgg', 1131788638, 'jingyiwong02@gmail.com', '71, TAMAN MAWAR SUNGAI RUAN', '1265hgfds', 23456.00);

-- --------------------------------------------------------

--
-- Table structure for table `policyholder_agedetail`
--

CREATE TABLE `policyholder_agedetail` (
  `PolicyHolder_DOB` date NOT NULL,
  `PolicyHolder_Age` int(3) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `policyholder_agedetail`
--

INSERT INTO `policyholder_agedetail` (`PolicyHolder_DOB`, `PolicyHolder_Age`) VALUES
('1975-02-08', 49),
('1985-06-18', 39),
('1986-05-21', 38),
('1988-02-23', 36),
('1990-01-23', 34),
('1990-11-04', 34),
('1992-07-17', 32),
('1995-03-05', 29),
('1997-10-18', 27),
('1999-09-09', 25),
('2020-10-10', 4);

-- --------------------------------------------------------

--
-- Table structure for table `policyholder_details`
--

CREATE TABLE `policyholder_details` (
  `PolicyHolder_FirstName` varchar(30) DEFAULT NULL,
  `PolicyHolder_LastName` varchar(15) DEFAULT NULL,
  `PolicyHolder_DOB` date DEFAULT NULL,
  `PolicyHolder_NRIC` bigint(20) UNSIGNED NOT NULL,
  `PolicyHolder_Gender` varchar(1) DEFAULT NULL,
  `PolicyHolder_Nationality` varchar(35) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `policyholder_details`
--

INSERT INTO `policyholder_details` (`PolicyHolder_FirstName`, `PolicyHolder_LastName`, `PolicyHolder_DOB`, `PolicyHolder_NRIC`, `PolicyHolder_Gender`, `PolicyHolder_Nationality`) VALUES
('JING YI', 'WONG', '2020-10-10', 123456789056, 'F', 'Malaysian'),
('Muhamad Abu Bakar', 'Bin Rashid', '1975-02-08', 750208080189, 'M', 'Malaysian'),
('Kim', 'Tan', '1985-06-18', 850618035679, 'M', 'Malaysian'),
('Angelina', 'Anak Kawau', '1986-05-21', 860521060302, 'F', 'Malaysian'),
('Mei', 'Lim', '1988-02-23', 880223075612, 'F', 'Malaysian'),
('Ahmad', 'Nor', '1990-01-23', 900123045678, 'M', 'Malaysian'),
('James', 'Smith', '1990-11-04', 901104021123, 'M', 'Malaysian'),
('Muthu', 'Krishnan', '1992-07-17', 920717085611, 'M', 'Malaysian'),
('Siti', 'Rahman', '1995-03-05', 950305145634, 'F', 'Malaysian'),
('Thambiran', 'Vasuthevan', '1997-10-18', 971018020561, 'M', 'Malaysian'),
('Hui Ling', 'Wong', '1999-09-09', 990909040344, 'F', 'Malaysian');

-- --------------------------------------------------------

--
-- Table structure for table `policy_coveragedetail`
--

CREATE TABLE `policy_coveragedetail` (
  `Coverage_ID` int(1) NOT NULL,
  `Coverage_Detail` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `policy_coveragedetail`
--

INSERT INTO `policy_coveragedetail` (`Coverage_ID`, `Coverage_Detail`) VALUES
(1, 'Collision'),
(1, 'Death'),
(1, 'Fire of theft'),
(1, 'Impact damage'),
(1, 'Injuries'),
(1, 'NCD protection'),
(1, 'Property'),
(1, 'Tow charges'),
(2, 'Third party Death'),
(2, 'Third party Injuries'),
(2, 'Third party Property');

-- --------------------------------------------------------

--
-- Table structure for table `policy_extension`
--

CREATE TABLE `policy_extension` (
  `Extension_ID` int(1) NOT NULL,
  `Extension_Type` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `policy_extension`
--

INSERT INTO `policy_extension` (`Extension_ID`, `Extension_Type`) VALUES
(1, 'convulsion of nature'),
(2, 'breakage of glass'),
(3, 'vehicle accessories');

-- --------------------------------------------------------

--
-- Table structure for table `policy_info`
--

CREATE TABLE `policy_info` (
  `Info_ID` int(3) NOT NULL,
  `Policy_PremiumAmount` decimal(7,2) DEFAULT NULL,
  `Policy_NCD` int(2) DEFAULT NULL,
  `Policy_Betterment` int(2) DEFAULT NULL,
  `Extension_ID` int(1) DEFAULT NULL,
  `Policy_LiabilityCoverageLimit` int(10) DEFAULT NULL,
  `Coverage_ID` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `policy_info`
--

INSERT INTO `policy_info` (`Info_ID`, `Policy_PremiumAmount`, `Policy_NCD`, `Policy_Betterment`, `Extension_ID`, `Policy_LiabilityCoverageLimit`, `Coverage_ID`) VALUES
(1, 2700.00, 15, 35, 1, 3000000, 1),
(2, 3200.00, 0, 0, NULL, 3000000, 1),
(3, 3100.00, 30, 0, 2, 3000000, 1),
(4, 3100.00, 0, 15, 1, 3000000, 1),
(5, 2200.00, 0, 20, NULL, 3000000, 2),
(6, 2400.00, 10, 5, 1, 3000000, 1),
(7, 3300.00, 0, 10, NULL, 3000000, 2),
(8, 2800.00, 20, 5, 2, 3000000, 1),
(9, 2500.00, 5, 0, 1, 3000000, 2),
(10, 2700.00, 10, 15, 3, 3000000, 1),
(11, 54300.00, 20, 50, 1, 30000, 1),
(65, 54300.00, 30, 50, 2, 30000, 1);

-- --------------------------------------------------------

--
-- Table structure for table `vehicle`
--

CREATE TABLE `vehicle` (
  `Vehicle_RegNum` varchar(12) NOT NULL,
  `Vehicle_Type` varchar(20) DEFAULT NULL,
  `Vehicle_Brand` varchar(20) DEFAULT NULL,
  `Vehicle_ManufactureYear` year(4) DEFAULT NULL,
  `Vehicle_Mileage_KM` int(10) DEFAULT NULL,
  `Vehicle_PuchaseDate` date DEFAULT NULL,
  `Vehicle_EngineID` varchar(10) DEFAULT NULL,
  `PolicyHolder_ID` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicle`
--

INSERT INTO `vehicle` (`Vehicle_RegNum`, `Vehicle_Type`, `Vehicle_Brand`, `Vehicle_ManufactureYear`, `Vehicle_Mileage_KM`, `Vehicle_PuchaseDate`, `Vehicle_EngineID`, `PolicyHolder_ID`) VALUES
('876543HGFDSA', 'yhgtfds', 'hgdfsa', '2008', 7, '2025-01-10', 'wesfg', 11),
('BSA6017', 'Sedan', 'Toyota', '2015', 20203, '2015-06-10', '52WVC10338', 1),
('ERT7865', 'Sedan', 'Proton', '2019', 67891, '2019-11-25', '67HJK67890', 9),
('FDR4581', 'SUV', 'Mazda', '2022', 46732, '2022-08-08', '57KDC53124', 4),
('JKL3219', 'SUV', 'Nissan', '2021', 54321, '2021-03-15', '45NMZ12345', 8),
('LYU8901', 'Hatchback', 'Perodua', '2016', 98432, '2016-11-10', '90GHF67231', 5),
('MKA3298', 'SUV', 'Toyota', '2018', 65781, '2018-09-15', '84JSK29273', 1),
('PGP82', 'Compact', 'Perodua', '2009', 113254, '2010-01-30', '73FVD17873', 5),
('POI5567', 'Hatchback', 'Kia', '2020', 89012, '2020-07-30', '23LKJ32145', 10),
('PRL2243', 'Wagon', 'Proton', '2020', 34875, '2020-07-08', '76GDX32143', 3),
('QAA1342', 'SUV', 'Honda', '2023', 113671, '2023-12-01', '390CNF6531', 2),
('VGT5532', 'Sedan', 'Honda', '2017', 78123, '2017-05-20', '32BCF73823', 7),
('WB223X', 'Pickup', 'Ford', '2019', 25567, '2019-03-20', '21JHG56322', 4),
('ZXC1023', 'Compact', 'Proton', '2021', 12451, '2021-04-12', '17JKF23178', 6);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `additionaldriver`
--
ALTER TABLE `additionaldriver`
  ADD PRIMARY KEY (`Driver_ID`),
  ADD KEY `Driver_NRIC` (`Driver_NRIC`),
  ADD KEY `Vehicle_RegNum` (`Vehicle_RegNum`);

--
-- Indexes for table `application`
--
ALTER TABLE `application`
  ADD PRIMARY KEY (`Application_ID`),
  ADD KEY `Policy_ID` (`Policy_ID`),
  ADD KEY `Vehicle_RegNum` (`Vehicle_RegNum`);

--
-- Indexes for table `bill`
--
ALTER TABLE `bill`
  ADD PRIMARY KEY (`Bill_ID`),
  ADD KEY `Policy_ID` (`Policy_ID`);

--
-- Indexes for table `claim`
--
ALTER TABLE `claim`
  ADD PRIMARY KEY (`Claim_ID`),
  ADD KEY `Application_ID` (`Application_ID`);

--
-- Indexes for table `claim_history`
--
ALTER TABLE `claim_history`
  ADD PRIMARY KEY (`ClaimHistory_ID`),
  ADD KEY `Claim_ID` (`Claim_ID`);

--
-- Indexes for table `commercial`
--
ALTER TABLE `commercial`
  ADD PRIMARY KEY (`Policy_ID`);

--
-- Indexes for table `driver_details`
--
ALTER TABLE `driver_details`
  ADD PRIMARY KEY (`Driver_NRIC`);

--
-- Indexes for table `personal`
--
ALTER TABLE `personal`
  ADD PRIMARY KEY (`Policy_ID`);

--
-- Indexes for table `policy`
--
ALTER TABLE `policy`
  ADD PRIMARY KEY (`Policy_ID`),
  ADD KEY `Info_ID` (`Info_ID`);

--
-- Indexes for table `policyholder`
--
ALTER TABLE `policyholder`
  ADD PRIMARY KEY (`PolicyHolder_ID`),
  ADD KEY `PolicyHolder_NRIC` (`PolicyHolder_NRIC`);

--
-- Indexes for table `policyholder_agedetail`
--
ALTER TABLE `policyholder_agedetail`
  ADD PRIMARY KEY (`PolicyHolder_DOB`);

--
-- Indexes for table `policyholder_details`
--
ALTER TABLE `policyholder_details`
  ADD PRIMARY KEY (`PolicyHolder_NRIC`),
  ADD KEY `PolicyHolder_DOB` (`PolicyHolder_DOB`);

--
-- Indexes for table `policy_coveragedetail`
--
ALTER TABLE `policy_coveragedetail`
  ADD PRIMARY KEY (`Coverage_ID`,`Coverage_Detail`);

--
-- Indexes for table `policy_extension`
--
ALTER TABLE `policy_extension`
  ADD PRIMARY KEY (`Extension_ID`);

--
-- Indexes for table `policy_info`
--
ALTER TABLE `policy_info`
  ADD PRIMARY KEY (`Info_ID`),
  ADD KEY `Coverage_ID` (`Coverage_ID`),
  ADD KEY `Extension_ID` (`Extension_ID`);

--
-- Indexes for table `vehicle`
--
ALTER TABLE `vehicle`
  ADD PRIMARY KEY (`Vehicle_RegNum`),
  ADD KEY `PolicyHolder_ID` (`PolicyHolder_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bill`
--
ALTER TABLE `bill`
  MODIFY `Bill_ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `claim_history`
--
ALTER TABLE `claim_history`
  MODIFY `ClaimHistory_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `policy`
--
ALTER TABLE `policy`
  MODIFY `Policy_ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1013;

--
-- AUTO_INCREMENT for table `policyholder`
--
ALTER TABLE `policyholder`
  MODIFY `PolicyHolder_ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `policy_info`
--
ALTER TABLE `policy_info`
  MODIFY `Info_ID` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1017;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `additionaldriver`
--
ALTER TABLE `additionaldriver`
  ADD CONSTRAINT `additionaldriver_ibfk_1` FOREIGN KEY (`Driver_NRIC`) REFERENCES `driver_details` (`Driver_NRIC`),
  ADD CONSTRAINT `additionaldriver_ibfk_2` FOREIGN KEY (`Vehicle_RegNum`) REFERENCES `vehicle` (`Vehicle_RegNum`);

--
-- Constraints for table `application`
--
ALTER TABLE `application`
  ADD CONSTRAINT `application_ibfk_1` FOREIGN KEY (`Policy_ID`) REFERENCES `policy` (`Policy_ID`),
  ADD CONSTRAINT `application_ibfk_2` FOREIGN KEY (`Vehicle_RegNum`) REFERENCES `vehicle` (`Vehicle_RegNum`);

--
-- Constraints for table `bill`
--
ALTER TABLE `bill`
  ADD CONSTRAINT `bill_ibfk_1` FOREIGN KEY (`Policy_ID`) REFERENCES `policy` (`Policy_ID`);

--
-- Constraints for table `claim`
--
ALTER TABLE `claim`
  ADD CONSTRAINT `claim_ibfk_1` FOREIGN KEY (`Application_ID`) REFERENCES `application` (`Application_ID`);

--
-- Constraints for table `claim_history`
--
ALTER TABLE `claim_history`
  ADD CONSTRAINT `claim_history_ibfk_1` FOREIGN KEY (`Claim_ID`) REFERENCES `claim` (`Claim_ID`);

--
-- Constraints for table `commercial`
--
ALTER TABLE `commercial`
  ADD CONSTRAINT `commercial_ibfk_1` FOREIGN KEY (`Policy_ID`) REFERENCES `policy` (`Policy_ID`);

--
-- Constraints for table `personal`
--
ALTER TABLE `personal`
  ADD CONSTRAINT `personal_ibfk_1` FOREIGN KEY (`Policy_ID`) REFERENCES `policy` (`Policy_ID`);

--
-- Constraints for table `policy`
--
ALTER TABLE `policy`
  ADD CONSTRAINT `policy_ibfk_1` FOREIGN KEY (`Info_ID`) REFERENCES `policy_info` (`Info_ID`);

--
-- Constraints for table `policyholder`
--
ALTER TABLE `policyholder`
  ADD CONSTRAINT `policyholder_ibfk_1` FOREIGN KEY (`PolicyHolder_NRIC`) REFERENCES `policyholder_details` (`PolicyHolder_NRIC`);

--
-- Constraints for table `policyholder_details`
--
ALTER TABLE `policyholder_details`
  ADD CONSTRAINT `policyholder_details_ibfk_1` FOREIGN KEY (`PolicyHolder_DOB`) REFERENCES `policyholder_agedetail` (`PolicyHolder_DOB`);

--
-- Constraints for table `policy_info`
--
ALTER TABLE `policy_info`
  ADD CONSTRAINT `policy_info_ibfk_1` FOREIGN KEY (`Coverage_ID`) REFERENCES `policy_coveragedetail` (`Coverage_ID`),
  ADD CONSTRAINT `policy_info_ibfk_2` FOREIGN KEY (`Extension_ID`) REFERENCES `policy_extension` (`Extension_ID`);

--
-- Constraints for table `vehicle`
--
ALTER TABLE `vehicle`
  ADD CONSTRAINT `vehicle_ibfk_1` FOREIGN KEY (`PolicyHolder_ID`) REFERENCES `policyholder` (`PolicyHolder_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
