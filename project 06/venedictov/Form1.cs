using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.IO;
using System.Linq;
using System.Text.Json;
using System.Windows.Forms;

namespace venedictov
{
    public partial class Form1 : Form
    {
        private BindingList<Student> students = new BindingList<Student>();
        private bool isSaved = true;
        public Form1()
        {
            InitializeComponent();
            dataGridView1.DataSource = students;
        }

        private void Form1_Load(object sender, EventArgs e)
        {

        }
        

        private bool ValidateFields()
        {
            if (string.IsNullOrWhiteSpace(txtLastName.Text) || string.IsNullOrWhiteSpace(txtFirstName.Text) ||
                string.IsNullOrWhiteSpace(txtMiddleName.Text) || string.IsNullOrWhiteSpace(txtCourse.Text) ||
                string.IsNullOrWhiteSpace(txtGroup.Text))
            {
                MessageBox.Show("Все поля должны быть заполнены.", "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                return false;
            }
            if (!char.IsUpper(txtLastName.Text[0]) || !char.IsUpper(txtFirstName.Text[0]) || !char.IsUpper(txtMiddleName.Text[0]))
            {
                MessageBox.Show("ФИО должно начинаться с заглавной буквы.", "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                return false;
            }
            if (!int.TryParse(txtCourse.Text, out int course) || course < 1 || course > 6)
            {
                MessageBox.Show("Курс должен быть числом от 1 до 6.", "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                return false;
            }
            return true;
        }

        private void MainForm_FormClosing(object sender, FormClosingEventArgs e)
        {
            if (!isSaved && MessageBox.Show("Сохранить изменения перед выходом?", "Выход", MessageBoxButtons.YesNo) == DialogResult.Yes)
            {
                btnSave_Click_1(sender, e);
            }
        }

        private void label1_Click(object sender, EventArgs e)
        {

        }

        private void btnAdd_Click_1(object sender, EventArgs e)
        {
            if (ValidateFields())
            {
                students.Add(new Student
                {
                    LastName = txtLastName.Text,
                    FirstName = txtFirstName.Text,
                    MiddleName = txtMiddleName.Text,
                    Course = int.Parse(txtCourse.Text),
                    Group = txtGroup.Text
                });
                isSaved = false;
            }
        }

        private void btnEdit_Click_1(object sender, EventArgs e)
        {
            if (dataGridView1.SelectedRows.Count > 0 && ValidateFields())
            {
                var student = (Student)dataGridView1.SelectedRows[0].DataBoundItem;
                student.LastName = txtLastName.Text;
                student.FirstName = txtFirstName.Text;
                student.MiddleName = txtMiddleName.Text;
                student.Course = int.Parse(txtCourse.Text);
                student.Group = txtGroup.Text;
                dataGridView1.Refresh();
                isSaved = false;
            }
        }

        private void btnDelete_Click_1(object sender, EventArgs e)
        {
            if (dataGridView1.SelectedRows.Count > 0)
            {
                students.Remove((Student)dataGridView1.SelectedRows[0].DataBoundItem);
                isSaved = false;
            }
        }

        private void btnSave_Click_1(object sender, EventArgs e)
        {
            using (SaveFileDialog saveFileDialog = new SaveFileDialog())
            {
                saveFileDialog.Filter = "JSON files (*.json)|*.json|All files (*.*)|*.*";
                if (saveFileDialog.ShowDialog() == DialogResult.OK)
                {
                    File.WriteAllText(saveFileDialog.FileName, JsonSerializer.Serialize(students));
                    isSaved = true;
                }
            }
        }

        private void btnLoad_Click_1(object sender, EventArgs e)
        {
            using (OpenFileDialog openFileDialog = new OpenFileDialog())
            {
                openFileDialog.Filter = "JSON files (*.json)|*.json|All files (*.*)|*.*";
                if (openFileDialog.ShowDialog() == DialogResult.OK)
                {
                    var data = File.ReadAllText(openFileDialog.FileName);
                    students = JsonSerializer.Deserialize<BindingList<Student>>(data);
                    dataGridView1.DataSource = students;
                }
            }
        }

        private void btnSortByLastName_Click_1(object sender, EventArgs e)
        {
            students = new BindingList<Student>(students.OrderBy(s => s.LastName).ToList());
            dataGridView1.DataSource = students;
        }

        private void btnSortByGroup_Click_1(object sender, EventArgs e)
        {
            students = new BindingList<Student>(students.OrderBy(s => s.Group).ToList());
            dataGridView1.DataSource = students;
        }
    }

    public class Student
    {
        public string LastName { get; set; }
        public string FirstName { get; set; }
        public string MiddleName { get; set; }
        public int Course { get; set; }
        public string Group { get; set; }
    }
}




