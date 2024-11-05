INSERT INTO user_account (id, name, username, email, password, isDeleted, isBlocked, profilePicture) VALUES
(1, 'Ana Silva', 'anasilva', 'ana.silva@example.com', 'password123', FALSE, FALSE, 'image/ana.png'),
(2, 'João Pereira', 'joaopereira', 'joao.pereira@example.com', 'password456', FALSE, FALSE, 'image/joao.png'),
(3, 'Maria Costa', 'mariacosta', 'maria.costa@example.com', 'password789', FALSE, TRUE, 'image/maria.png'),
(4, 'Pedro Sousa', 'pedrosousa', 'pedro.sousa@example.com', 'password101', TRUE, FALSE, 'image/pedro.png');

INSERT INTO admin (id, email, password) VALUES
(1, 'admin1@example.com', 'adminpass1'),
(2, 'admin2@example.com', 'adminpass2');

INSERT INTO location (id, name) VALUES
(1, 'Centro Comunitário A'),
(2, 'Parque B'),
(3, 'Escola C'),
(4, 'Instituição D');

INSERT INTO category (id, name) VALUES
(1, 'Limpeza Ambiental'),
(2, 'Apoio a Idosos'),
(3, 'Distribuição de Alimentos'),
(4, 'Apoio Escolar');

INSERT INTO event (id, name, description, start_date, end_date, visibility, capacity, creator_id, location_id, category_id) VALUES
(1, 'Limpeza do Parque da Cidade', 'Evento para limpar o Parque da Cidade.', '2023-11-15 09:00:00', '2023-11-15 12:00:00', 'public', 50, 1, 2, 1),
(2, 'Distribuição de Refeições', 'Distribuição de refeições para pessoas em situação de rua.', '2023-11-20 18:00:00', '2023-11-20 21:00:00', 'public', 30, 2, 1, 3),
(3, 'Apoio Escolar', 'Aulas de reforço escolar para crianças de baixa renda.', '2023-12-01 14:00:00', '2023-12-01 17:00:00', 'private', 20, 1, 3, 4);

INSERT INTO participator (participator_id, event_id) VALUES
(2, 1),
(3, 1),
(4, 2),
(1, 3);

INSERT INTO question_review (id, question_text) VALUES
(1, 'O evento foi bem organizado?'),
(2, 'A estrutura do evento atendeu às necessidades?'),
(3, 'Os voluntários foram bem recebidos e orientados?'),
(4, 'O evento cumpriu o objetivo proposto?'),
(5, 'Recomendaria este evento a outras pessoas?');

INSERT INTO answer_review (answered_by_id, question_id, about_event_id, rating, date) VALUES
(2, 1, 1, 4, '2023-11-16'),
(3, 2, 1, 5, '2023-11-16'),
(1, 1, 3, 3, '2023-12-02');