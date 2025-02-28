using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.IO;
using System.Linq;
using System.Text.Json;
using System.Windows.Forms;

namespace venedict
{
    public partial class Form1 : Form
    {
        private BindingList<Student> students = new BindingList<Student>();
        private bool isSaved = true;
        private const string filePath = "students.json";

        public Form1()
        {
            InitializeComponent();
            SetupDataGridView();
            dataGridView1.DataSource = students;
            LoadData();
        }
        private void SetupDataGridView()
        {
            dataGridView1.AutoGenerateColumns = false; // Отключаем автогенерацию колонок

            dataGridView1.Columns.Add(new DataGridViewTextBoxColumn
            {
                DataPropertyName = "LastName",
                HeaderText = "Фамилия",
                Width = 120
            });

            dataGridView1.Columns.Add(new DataGridViewTextBoxColumn
            {
                DataPropertyName = "FirstName",
                HeaderText = "Имя",
                Width = 100
            });

            dataGridView1.Columns.Add(new DataGridViewTextBoxColumn
            {
                DataPropertyName = "MiddleName",
                HeaderText = "Отчество",
                Width = 120
            });

            dataGridView1.Columns.Add(new DataGridViewTextBoxColumn
            {
                DataPropertyName = "Course",
                HeaderText = "Курс",
                Width = 50
            });

            dataGridView1.Columns.Add(new DataGridViewTextBoxColumn
            {
                DataPropertyName = "Group",
                HeaderText = "Группа",
                Width = 100
            });

            dataGridView1.SelectionMode = DataGridViewSelectionMode.FullRowSelect; // Выделение всей строки
            dataGridView1.MultiSelect = false; // Запрещаем множественный выбор
            dataGridView1.ReadOnly = true; // Делаем таблицу только для просмотра
            dataGridView1.AllowUserToAddRows = false; // Убираем пустую строку внизу
        }


        private void btnAdd_Click(object sender, EventArgs e)
        {

        }

        private void btnEdit_Click(object sender, EventArgs e)
        {

        }

        private void btnDelete_Click(object sender, EventArgs e)
        {

        }

        private void btnSortByLastName_Click(object sender, EventArgs e)
        {

        }

        private void btnSortByGroup_Click(object sender, EventArgs e)
        {

        }

        private void btnSave_Click(object sender, EventArgs e)
        {

        }

        private void LoadData()
        {
            if (File.Exists(filePath))
            {
                var data = File.ReadAllText(filePath);
                students = JsonSerializer.Deserialize<BindingList<Student>>(data);
                dataGridView1.DataSource = students;
            }
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
                btnSave_Click(sender, e);
            }
        }

        private void textBox1_TextChanged(object sender, EventArgs e)
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
        private void btnLoad_Click(object sender, EventArgs e)
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

        public class Student
        {
            public string LastName { get; set; }
            public string FirstName { get; set; }
            public string MiddleName { get; set; }
            public int Course { get; set; }
            public string Group { get; set; }
        }

        
    }
}
