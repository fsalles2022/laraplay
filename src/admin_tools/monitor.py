import tkinter as tk
from tkinter import ttk, messagebox
import mysql.connector # ou sqlite3, dependendo do seu Laravel
from datetime import datetime

class LaravelPlaygroundMonitor:
    def __init__(self, root):
        self.root = root
        self.root.title("Admin Monitor - PHP Playground")
        self.root.geometry("800x500")
        self.root.configure(bg="#0f172a") # Slate 900 (estilo Tailwind)

        self.setup_ui()
        self.atualizar_dados()

    def setup_ui(self):
        # Estilo moderno
        style = ttk.Style()
        style.theme_use("clam")
        style.configure("Treeview", background="#1e293b", foreground="white", fieldbackground="#1e293b", borderwidth=0)
        style.map("Treeview", background=[('selected', '#ef4444')]) # Red 500

        # Header
        lbl = tk.Label(self.root, text="RECENT SNIPPETS EXECUTION", font=("Arial", 12, "bold"),
                       bg="#0f172a", fg="#f1f5f9", pady=10)
        lbl.pack()

        # Tabela (Treeview)
        self.tree = ttk.Treeview(self.root, columns=("ID", "User", "Title", "Tag", "Result"), show='headings')
        self.tree.heading("ID", text="ID")
        self.tree.heading("User", text="User ID")
        self.tree.heading("Title", text="Título")
        self.tree.heading("Tag", text="Tag")
        self.tree.heading("Result", text="Output Preview")

        self.tree.column("ID", width=50)
        self.tree.column("Result", width=300)
        self.tree.pack(expand=True, fill="both", padx=20, pady=10)

        # Botão de Refresh
        self.btn_refresh = tk.Button(self.root, text="ATUALIZAR LOGS", command=self.atualizar_dados,
                                    bg="#dc2626", fg="white", font=("Arial", 10, "bold"), relief="flat", padx=20)
        self.btn_refresh.pack(pady=10)

    def conectar_db(self):
        # Substitua pelas credenciais do seu .env
        return mysql.connector.connect(
            host="localhost",
            user="root",
            password="",
            database="nome_do_seu_banco"
        )

    def atualizar_dados(self):
        try:
            # Limpa tabela
            for i in self.tree.get_children():
                self.tree.delete(i)

            db = self.conectar_db()
            cursor = db.cursor()
            # Puxa os dados da sua Model Snippet (tabela snippets)
            cursor.execute("SELECT id, user_id, title, tag, result FROM snippets ORDER BY created_at DESC LIMIT 20")

            for row in cursor.fetchall():
                # Truncar o resultado para não quebrar a tabela
                res_preview = (row[4][:50] + '..') if row[4] and len(row[4]) > 50 else row[4]
                self.tree.insert("", "end", values=(row[0], row[1], row[2], row[3], res_preview))

            db.close()
        except Exception as e:
            messagebox.showerror("Erro de Conexão", f"Não foi possível ler o banco de dados:\n{e}")

if __name__ == "__main__":
    root = tk.Tk()
    app = LaravelPlaygroundMonitor(root)
    root.mainloop()
