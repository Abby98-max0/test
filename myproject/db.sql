CREATE DATABASE IF NOT EXISTS ngo_rmf_enhanced CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ngo_rmf_enhanced;

CREATE TABLE IF NOT EXISTS assessments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  service_area VARCHAR(100) NOT NULL,
  likelihood TINYINT NOT NULL,
  impact TINYINT NOT NULL,
  vulnerabilities TEXT,
  controls_selected TEXT,
  total_score DECIMAL(6,2) NOT NULL,
  risk_profile ENUM('LOW','MEDIUM','HIGH','CRITICAL') NOT NULL,
  mitigation_plan TEXT,
  status ENUM('Pending','Implemented','Verified') DEFAULT 'Pending',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Mapping table: maps input risk factors to NIST SP 800-53 families & recommended controls
CREATE TABLE IF NOT EXISTS control_mappings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  factor_key VARCHAR(100) NOT NULL, -- e.g., 'poor_backups', 'single_server'
  factor_description TEXT NOT NULL,
  nist_family VARCHAR(50) NOT NULL,
  nist_controls TEXT NOT NULL
);

-- Seed a few mappings
INSERT INTO control_mappings (factor_key, factor_description, nist_family, nist_controls) VALUES
('poor_backups', 'Lack of recent backups / untested restores', 'CP - Contingency Planning', 'CP-6, CP-9'),
('single_server', 'Single point of hosting (no redundancy)', 'SC - System & Communications Protection', 'SC-5, SC-6, SC-7'),
('no_waf', 'No Web Application Firewall or filtering', 'SC - System & Communications Protection', 'SC-7, SI-4'),
('no_monitoring', 'Lack of traffic monitoring and anomaly detection', 'SI - System and Information Integrity', 'SI-4, IR-4'),
('financial_weakness', 'Insufficient financial resilience for emergency mitigation', 'PM - Program Management', 'PM-9');
