
-- إنشاء قاعدة البيانات
CREATE DATABASE IF NOT EXISTS health_habits_db;
USE health_habits_db;

-- جدول السجلات الصحية
CREATE TABLE IF NOT EXISTS health_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    activity_type ENUM('walk', 'run', 'gym', 'yoga', 'meditation', 'water', 'sleep', 'weight', 'reading', 'learning') NOT NULL,
    value DECIMAL(10,2) NOT NULL,
    unit ENUM('minutes', 'hours', 'km', 'cups', 'kg', 'pages') DEFAULT 'minutes',
    notes TEXT,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- إضافة بعض السجلات الافتراضية
INSERT INTO health_logs (activity_type, value, unit, notes, date) VALUES
('walk', 30, 'minutes', 'مشي صباحي في الحديقة', '2023-10-20 07:30:00'),
('water', 8, 'cups', 'شرب كمية جيدة من الماء اليوم', '2023-10-20 20:00:00'),
('gym', 45, 'minutes', 'تمرين قوة وكارديو', '2023-10-19 18:00:00'),
('meditation', 15, 'minutes', 'جلسة تأمل هادئة', '2023-10-19 21:00:00'),
('sleep', 7.5, 'hours', 'نوم متواصل وجيد', '2023-10-20 06:00:00'),
('reading', 20, 'minutes', 'قراءة كتاب تطوير الذات', '2023-10-18 22:00:00'),
('run', 5, 'km', 'جري مسائي حول البحيرة', '2023-10-17 17:30:00'),
('yoga', 30, 'minutes', 'تمارين يوجا للمرونة', '2023-10-16 08:00:00'),
('water', 6, 'cups', 'احتساب كمية الماء', '2023-10-16 21:00:00'),
('learning', 40, 'minutes', 'تعلم لغة جديدة', '2023-10-15 19:00:00');