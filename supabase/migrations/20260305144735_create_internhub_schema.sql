/*
  # InternHub Database Schema
  
  1. New Tables
    - coordinators
      - id (uuid, primary key)
      - name (text)
      - email (text, unique)
      - password_hash (text)
      - first_login (boolean)
      - created_at (timestamptz)
    
    - classes
      - id (uuid, primary key)
      - course (text)
      - sigla (text)
      - year (integer)
      - coordinator_id (uuid, foreign key)
      - created_at (timestamptz)
    
    - students
      - id (uuid, primary key)
      - name (text)
      - email (text, unique)
      - password_hash (text)
      - class_id (uuid, foreign key)
      - first_login (boolean)
      - created_at (timestamptz)
    
    - companies
      - id (uuid, primary key)
      - name (text)
      - address (text)
      - email (text)
      - phone (text)
      - created_at (timestamptz)
    
    - supervisors
      - id (uuid, primary key)
      - name (text)
      - email (text, unique)
      - password_hash (text)
      - company_id (uuid, foreign key)
      - first_login (boolean)
      - created_at (timestamptz)
    
    - internships
      - id (uuid, primary key)
      - company_id (uuid, foreign key)
      - title (text)
      - start_date (date)
      - end_date (date)
      - total_hours_required (integer)
      - min_hours_day (numeric)
      - lunch_break_minutes (integer)
      - status (text)
      - created_at (timestamptz)
    
    - student_internships
      - id (uuid, primary key)
      - student_id (uuid, foreign key)
      - internship_id (uuid, foreign key)
      - assigned_at (timestamptz)
    
    - supervisor_internships
      - id (uuid, primary key)
      - supervisor_id (uuid, foreign key)
      - internship_id (uuid, foreign key)
      - assigned_at (timestamptz)
    
    - hours
      - id (uuid, primary key)
      - student_id (uuid, foreign key)
      - internship_id (uuid, foreign key)
      - date (date)
      - start_time (time)
      - end_time (time)
      - duration_hours (numeric)
      - status (text)
      - supervisor_reviewed_by (uuid, foreign key)
      - supervisor_comment (text)
      - created_at (timestamptz)
      - reviewed_at (timestamptz)
    
    - reports
      - id (uuid, primary key)
      - student_id (uuid, foreign key)
      - title (text)
      - file_path (text)
      - status (text)
      - feedback (text)
      - created_at (timestamptz)
    
    - conversations
      - id (uuid, primary key)
      - user1_role (text)
      - user1_id (uuid)
      - user2_role (text)
      - user2_id (uuid)
      - convo_key (text, unique)
      - created_at (timestamptz)
    
    - messages
      - id (uuid, primary key)
      - conversation_id (uuid, foreign key)
      - sender_role (text)
      - sender_id (uuid)
      - body (text)
      - created_at (timestamptz)
      - read_at (timestamptz)
  
  2. Security
    - Enable RLS on all tables
    - Add policies for authenticated users to access their own data
    - Add policies for coordinators to access their class data
    - Add policies for supervisors to access their internship data
*/

-- Create coordinators table
CREATE TABLE IF NOT EXISTS coordinators (
  id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
  name text NOT NULL,
  email text UNIQUE NOT NULL,
  password_hash text NOT NULL,
  first_login boolean DEFAULT true,
  created_at timestamptz DEFAULT now()
);

ALTER TABLE coordinators ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Users can view own coordinator profile"
  ON coordinators FOR SELECT
  TO authenticated
  USING (auth.uid() = id);

CREATE POLICY "Users can update own coordinator profile"
  ON coordinators FOR UPDATE
  TO authenticated
  USING (auth.uid() = id)
  WITH CHECK (auth.uid() = id);

-- Create companies table
CREATE TABLE IF NOT EXISTS companies (
  id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
  name text NOT NULL,
  address text,
  email text,
  phone text,
  created_at timestamptz DEFAULT now()
);

ALTER TABLE companies ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Authenticated users can view companies"
  ON companies FOR SELECT
  TO authenticated
  USING (true);

-- Create classes table
CREATE TABLE IF NOT EXISTS classes (
  id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
  course text NOT NULL,
  sigla text NOT NULL,
  year integer,
  coordinator_id uuid REFERENCES coordinators(id) ON DELETE RESTRICT,
  created_at timestamptz DEFAULT now(),
  UNIQUE(sigla, year)
);

ALTER TABLE classes ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Coordinators can view their classes"
  ON classes FOR SELECT
  TO authenticated
  USING (coordinator_id = auth.uid());

CREATE POLICY "Coordinators can update their classes"
  ON classes FOR UPDATE
  TO authenticated
  USING (coordinator_id = auth.uid())
  WITH CHECK (coordinator_id = auth.uid());

-- Create students table
CREATE TABLE IF NOT EXISTS students (
  id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
  name text NOT NULL,
  email text UNIQUE NOT NULL,
  password_hash text NOT NULL,
  class_id uuid REFERENCES classes(id) ON DELETE RESTRICT,
  first_login boolean DEFAULT true,
  created_at timestamptz DEFAULT now()
);

ALTER TABLE students ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Students can view own profile"
  ON students FOR SELECT
  TO authenticated
  USING (auth.uid() = id);

CREATE POLICY "Students can update own profile"
  ON students FOR UPDATE
  TO authenticated
  USING (auth.uid() = id)
  WITH CHECK (auth.uid() = id);

CREATE POLICY "Coordinators can view students in their classes"
  ON students FOR SELECT
  TO authenticated
  USING (
    EXISTS (
      SELECT 1 FROM classes
      WHERE classes.id = students.class_id
      AND classes.coordinator_id = auth.uid()
    )
  );

-- Create supervisors table
CREATE TABLE IF NOT EXISTS supervisors (
  id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
  name text NOT NULL,
  email text UNIQUE NOT NULL,
  password_hash text NOT NULL,
  company_id uuid REFERENCES companies(id) ON DELETE RESTRICT,
  first_login boolean DEFAULT true,
  created_at timestamptz DEFAULT now()
);

ALTER TABLE supervisors ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Supervisors can view own profile"
  ON supervisors FOR SELECT
  TO authenticated
  USING (auth.uid() = id);

CREATE POLICY "Supervisors can update own profile"
  ON supervisors FOR UPDATE
  TO authenticated
  USING (auth.uid() = id)
  WITH CHECK (auth.uid() = id);

-- Create internships table
CREATE TABLE IF NOT EXISTS internships (
  id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
  company_id uuid REFERENCES companies(id) ON DELETE RESTRICT,
  title text,
  start_date date NOT NULL,
  end_date date NOT NULL,
  total_hours_required integer NOT NULL,
  min_hours_day numeric(4,1) DEFAULT 6.0,
  lunch_break_minutes integer DEFAULT 60,
  status text DEFAULT 'active' CHECK (status IN ('active', 'completed')),
  created_at timestamptz DEFAULT now()
);

ALTER TABLE internships ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Authenticated users can view internships"
  ON internships FOR SELECT
  TO authenticated
  USING (true);

-- Create student_internships table
CREATE TABLE IF NOT EXISTS student_internships (
  id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
  student_id uuid REFERENCES students(id) ON DELETE CASCADE,
  internship_id uuid REFERENCES internships(id) ON DELETE RESTRICT,
  assigned_at timestamptz DEFAULT now(),
  UNIQUE(student_id)
);

ALTER TABLE student_internships ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Students can view own internship assignments"
  ON student_internships FOR SELECT
  TO authenticated
  USING (student_id = auth.uid());

CREATE POLICY "Coordinators can view internship assignments for their students"
  ON student_internships FOR SELECT
  TO authenticated
  USING (
    EXISTS (
      SELECT 1 FROM students s
      JOIN classes c ON s.class_id = c.id
      WHERE s.id = student_internships.student_id
      AND c.coordinator_id = auth.uid()
    )
  );

-- Create supervisor_internships table
CREATE TABLE IF NOT EXISTS supervisor_internships (
  id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
  supervisor_id uuid REFERENCES supervisors(id) ON DELETE CASCADE,
  internship_id uuid REFERENCES internships(id) ON DELETE RESTRICT,
  assigned_at timestamptz DEFAULT now(),
  UNIQUE(supervisor_id)
);

ALTER TABLE supervisor_internships ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Supervisors can view own internship assignments"
  ON supervisor_internships FOR SELECT
  TO authenticated
  USING (supervisor_id = auth.uid());

-- Create hours table
CREATE TABLE IF NOT EXISTS hours (
  id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
  student_id uuid REFERENCES students(id) ON DELETE CASCADE,
  internship_id uuid REFERENCES internships(id) ON DELETE RESTRICT,
  date date NOT NULL,
  start_time time NOT NULL,
  end_time time NOT NULL,
  duration_hours numeric(4,1) NOT NULL,
  status text DEFAULT 'pending' CHECK (status IN ('pending', 'approved', 'rejected')),
  supervisor_reviewed_by uuid REFERENCES supervisors(id) ON DELETE SET NULL,
  supervisor_comment text,
  created_at timestamptz DEFAULT now(),
  reviewed_at timestamptz
);

ALTER TABLE hours ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Students can view own hours"
  ON hours FOR SELECT
  TO authenticated
  USING (student_id = auth.uid());

CREATE POLICY "Students can insert own hours"
  ON hours FOR INSERT
  TO authenticated
  WITH CHECK (student_id = auth.uid());

CREATE POLICY "Students can update own pending hours"
  ON hours FOR UPDATE
  TO authenticated
  USING (student_id = auth.uid() AND status = 'pending')
  WITH CHECK (student_id = auth.uid() AND status = 'pending');

CREATE POLICY "Supervisors can view hours for their internships"
  ON hours FOR SELECT
  TO authenticated
  USING (
    EXISTS (
      SELECT 1 FROM supervisor_internships
      WHERE supervisor_internships.internship_id = hours.internship_id
      AND supervisor_internships.supervisor_id = auth.uid()
    )
  );

CREATE POLICY "Supervisors can update hours for their internships"
  ON hours FOR UPDATE
  TO authenticated
  USING (
    EXISTS (
      SELECT 1 FROM supervisor_internships
      WHERE supervisor_internships.internship_id = hours.internship_id
      AND supervisor_internships.supervisor_id = auth.uid()
    )
  )
  WITH CHECK (
    EXISTS (
      SELECT 1 FROM supervisor_internships
      WHERE supervisor_internships.internship_id = hours.internship_id
      AND supervisor_internships.supervisor_id = auth.uid()
    )
  );

-- Create reports table
CREATE TABLE IF NOT EXISTS reports (
  id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
  student_id uuid REFERENCES students(id) ON DELETE CASCADE,
  title text,
  file_path text,
  status text DEFAULT 'pending' CHECK (status IN ('pending', 'approved', 'rejected')),
  feedback text,
  created_at timestamptz DEFAULT now()
);

ALTER TABLE reports ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Students can view own reports"
  ON reports FOR SELECT
  TO authenticated
  USING (student_id = auth.uid());

CREATE POLICY "Students can insert own reports"
  ON reports FOR INSERT
  TO authenticated
  WITH CHECK (student_id = auth.uid());

CREATE POLICY "Coordinators can view reports for their students"
  ON reports FOR SELECT
  TO authenticated
  USING (
    EXISTS (
      SELECT 1 FROM students s
      JOIN classes c ON s.class_id = c.id
      WHERE s.id = reports.student_id
      AND c.coordinator_id = auth.uid()
    )
  );

CREATE POLICY "Coordinators can update reports for their students"
  ON reports FOR UPDATE
  TO authenticated
  USING (
    EXISTS (
      SELECT 1 FROM students s
      JOIN classes c ON s.class_id = c.id
      WHERE s.id = reports.student_id
      AND c.coordinator_id = auth.uid()
    )
  )
  WITH CHECK (
    EXISTS (
      SELECT 1 FROM students s
      JOIN classes c ON s.class_id = c.id
      WHERE s.id = reports.student_id
      AND c.coordinator_id = auth.uid()
    )
  );

-- Create conversations table
CREATE TABLE IF NOT EXISTS conversations (
  id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
  user1_role text NOT NULL CHECK (user1_role IN ('student', 'supervisor', 'coordinator', 'admin')),
  user1_id uuid NOT NULL,
  user2_role text NOT NULL CHECK (user2_role IN ('student', 'supervisor', 'coordinator', 'admin')),
  user2_id uuid NOT NULL,
  convo_key text UNIQUE NOT NULL,
  created_at timestamptz DEFAULT now()
);

ALTER TABLE conversations ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Users can view their own conversations"
  ON conversations FOR SELECT
  TO authenticated
  USING (user1_id = auth.uid() OR user2_id = auth.uid());

-- Create messages table
CREATE TABLE IF NOT EXISTS messages (
  id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
  conversation_id uuid REFERENCES conversations(id) ON DELETE CASCADE,
  sender_role text NOT NULL CHECK (sender_role IN ('student', 'supervisor', 'coordinator', 'admin')),
  sender_id uuid NOT NULL,
  body text NOT NULL,
  created_at timestamptz DEFAULT now(),
  read_at timestamptz
);

ALTER TABLE messages ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Users can view messages in their conversations"
  ON messages FOR SELECT
  TO authenticated
  USING (
    EXISTS (
      SELECT 1 FROM conversations
      WHERE conversations.id = messages.conversation_id
      AND (conversations.user1_id = auth.uid() OR conversations.user2_id = auth.uid())
    )
  );

CREATE POLICY "Users can insert messages in their conversations"
  ON messages FOR INSERT
  TO authenticated
  WITH CHECK (
    sender_id = auth.uid() AND
    EXISTS (
      SELECT 1 FROM conversations
      WHERE conversations.id = conversation_id
      AND (conversations.user1_id = auth.uid() OR conversations.user2_id = auth.uid())
    )
  );

-- Create indexes for better performance
CREATE INDEX IF NOT EXISTS idx_students_class_id ON students(class_id);
CREATE INDEX IF NOT EXISTS idx_hours_student_date ON hours(student_id, date);
CREATE INDEX IF NOT EXISTS idx_hours_internship_status ON hours(internship_id, status);
CREATE INDEX IF NOT EXISTS idx_reports_student_id ON reports(student_id);
CREATE INDEX IF NOT EXISTS idx_messages_conversation_id ON messages(conversation_id, created_at);
