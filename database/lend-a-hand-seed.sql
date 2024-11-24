DROP SCHEMA IF EXISTS lbaw2414 CASCADE;

CREATE SCHEMA lbaw2414;

SET search_path TO lbaw2414;

CREATE EXTENSION IF NOT EXISTS unaccent;

-- drop tables
DROP TABLE IF EXISTS unblock_notification CASCADE;
DROP TABLE IF EXISTS admin_action CASCADE;
DROP TABLE IF EXISTS event_reminder CASCADE;
DROP TABLE IF EXISTS event_cancelation CASCADE;
DROP TABLE IF EXISTS poll_result_notification CASCADE;
DROP TABLE IF EXISTS poll_reminder CASCADE;
DROP TABLE IF EXISTS comment_notification CASCADE;
DROP TABLE IF EXISTS event_update_notification CASCADE;
DROP TABLE IF EXISTS accepted_invite_notification CASCADE;
DROP TABLE IF EXISTS invite_notification CASCADE;
DROP TABLE IF EXISTS invite CASCADE;
DROP TABLE IF EXISTS unblock_request CASCADE;
DROP TABLE IF EXISTS admin CASCADE;
DROP TABLE IF EXISTS location CASCADE;
DROP TABLE IF EXISTS category CASCADE;
DROP TABLE IF EXISTS notification CASCADE;
DROP TABLE IF EXISTS poll_response CASCADE;
DROP TABLE IF EXISTS poll CASCADE;
DROP TABLE IF EXISTS vote CASCADE;
DROP TABLE IF EXISTS comment CASCADE;
DROP TABLE IF EXISTS answer_review CASCADE;
DROP TABLE IF EXISTS question_review CASCADE;
DROP TABLE IF EXISTS participator CASCADE;
DROP TABLE IF EXISTS event CASCADE;
DROP TABLE IF EXISTS user_account CASCADE;

-- drop indexes
DROP INDEX IF EXISTS idx_user_username;
DROP INDEX IF EXISTS idx_event_start_date;
DROP INDEX IF EXISTS idx_event_category;
DROP INDEX IF EXISTS idx_event_full_text_search;

-- drop triggers
DROP TRIGGER IF EXISTS event_search_update ON event;
DROP TRIGGER IF EXISTS trigger_prevent_duplicate_poll_response ON poll_response;
DROP TRIGGER IF EXISTS trigger_create_invite_notification ON invite;
DROP TRIGGER IF EXISTS trigger_accepted_invite_notification ON invite;
DROP TRIGGER IF EXISTS trigger_event_update_notification ON event;
DROP TRIGGER IF EXISTS trigger_comment_notification ON comment;
DROP TRIGGER IF EXISTS trigger_event_cancellation ON event;
DROP TRIGGER IF EXISTS trigger_poll_reminder ON poll;
DROP TRIGGER IF EXISTS trigger_event_reminder_notification ON event;
DROP TRIGGER IF EXISTS trigger_unblock_notification ON unblock_request;
DROP TRIGGER IF EXISTS trigger_block_notification ON user_account;

-- drop functions
DROP FUNCTION IF EXISTS event_search_update();
DROP FUNCTION IF EXISTS prevent_duplicate_poll_response();
DROP FUNCTION IF EXISTS create_invite_notification();
DROP FUNCTION IF EXISTS create_accepted_invite_notification();
DROP FUNCTION IF EXISTS create_event_update_notification();
DROP FUNCTION IF EXISTS create_comment_notification();
DROP FUNCTION IF EXISTS notify_event_cancellation();
DROP FUNCTION IF EXISTS create_poll_reminder();
DROP FUNCTION IF EXISTS create_event_reminder_notification();
DROP FUNCTION IF EXISTS create_unblock_notification();
DROP FUNCTION IF EXISTS create_block_notification();

-- create tables
CREATE TABLE user_account (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    username VARCHAR(255) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    isDeleted BOOLEAN DEFAULT FALSE,
    isBlocked BOOLEAN DEFAULT FALSE,
    profilePicture VARCHAR(255) DEFAULT 'default-avatar.png'
);

CREATE TABLE location (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE category (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) UNIQUE NOT NULL
);

CREATE TABLE "event" (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    start_date TIMESTAMP NOT NULL,
    end_date TIMESTAMP NOT NULL,
    visibility VARCHAR(50),
    capacity INT,
    creator_id INT REFERENCES user_account(id),
    location_id INT REFERENCES location(id),
    category_id INT REFERENCES category(id),
    banner VARCHAR(255) DEFAULT 'default-banner.png'
    CONSTRAINT ck_start_end CHECK (start_date < end_date)
);

CREATE TABLE participator (
    participator_id INT REFERENCES user_account(id),
    event_id INT REFERENCES event(id),
    PRIMARY KEY (participator_id, event_id)
);

CREATE TABLE question_review (
    id INT PRIMARY KEY,
    question_text VARCHAR(255) NOT NULL
);

CREATE TABLE answer_review (
    answered_by_id INT REFERENCES user_account(id),
    question_id INT NOT NULL REFERENCES question_review(id),
    about_event_id INT NOT NULL REFERENCES event(id),
    rating INT CHECK (rating >= 1 AND rating <= 5) NOT NULL,
    date DATE CHECK (date <= CURRENT_DATE) NOT NULL,
    PRIMARY KEY (answered_by_id, question_id, about_event_id)
);

CREATE TABLE comment (
    id SERIAL PRIMARY KEY,
    comment_by_id INT REFERENCES user_account(id),
    event_id INT REFERENCES event(id),
    text TEXT NOT NULL,
    is_pinned BOOLEAN,
    score INT DEFAULT 0,
    edited BOOLEAN DEFAULT FALSE NOT NULL,
    date TIMESTAMP NOT NULL CHECK (date <= CURRENT_TIMESTAMP)
);

CREATE TABLE vote (
    voted_by_id INT REFERENCES user_account(id),
    comment_id INT REFERENCES comment(id),
    type INT NOT NULL CHECK (type = -1 OR type = 1),
    PRIMARY KEY (voted_by_id, comment_id)
);

CREATE TABLE poll (
    id SERIAL PRIMARY KEY,
    event_id INT REFERENCES event(id),
    name VARCHAR(255) NOT NULL,
    start_time TIMESTAMP NOT NULL,
    end_time TIMESTAMP NOT NULL,
    options TEXT NOT NULL
);

CREATE TABLE poll_response (
    poll_id INT REFERENCES poll(id),
    user_id INT REFERENCES user_account(id),
    option TEXT NOT NULL,
    PRIMARY KEY (poll_id, user_id)
);

CREATE TABLE notification (
    id SERIAL PRIMARY KEY,
    emitted_by INT REFERENCES user_account(id),
    emitted_to INT REFERENCES user_account(id),
    date TIMESTAMP NOT NULL CHECK (date <= CURRENT_TIMESTAMP),
    viewed BOOLEAN DEFAULT FALSE NOT NULL
);

CREATE TABLE admin (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE unblock_request (
    id SERIAL PRIMARY KEY,
    decision_by_id INT REFERENCES admin(id),
    requested_by_id INT REFERENCES user_account(id),
    status VARCHAR(50) NOT NULL,
    requested_at TIMESTAMP NOT NULL CHECK (requested_at <= CURRENT_TIMESTAMP),
    processed_at TIMESTAMP NOT NULL CHECK (processed_at >= requested_at),
    decision VARCHAR(255) NOT NULL
);

CREATE TABLE invite (
    id SERIAL PRIMARY KEY,
    invite_by_id INT REFERENCES user_account(id),
    invite_to_id INT REFERENCES user_account(id),
    title VARCHAR(255) NOT NULL,
	accepted BOOLEAN,
    description TEXT NOT NULL,
    date TIMESTAMP NOT NULL CHECK (date <= CURRENT_TIMESTAMP),
    event_id INT REFERENCES event(id)
);

CREATE TABLE invite_notification (
    id INT REFERENCES notification(id),
    invite_id INT REFERENCES invite(id),
    PRIMARY KEY (id, invite_id)
);

CREATE TABLE accepted_invite_notification (
    id INT REFERENCES notification(id),
    invite_id INT REFERENCES invite(id),
    PRIMARY KEY (id, invite_id)
);

CREATE TABLE event_update_notification (
    id INT REFERENCES notification(id),
    event_id INT REFERENCES event(id)  ON DELETE CASCADE,
    PRIMARY KEY (id, event_id)
);

CREATE TABLE comment_notification (
    id INT REFERENCES notification(id),
    comment_id INT REFERENCES comment(id) ON DELETE CASCADE,
    PRIMARY KEY (id, comment_id)
);

CREATE TABLE poll_reminder (
    id INT REFERENCES notification(id),
    poll_id INT REFERENCES poll(id),
    PRIMARY KEY (id, poll_id)
);

CREATE TABLE event_cancelation (
    id INT REFERENCES notification(id),
    event_id INT REFERENCES event(id),
    PRIMARY KEY (id, event_id)
);

CREATE TABLE event_reminder (
    id INT REFERENCES notification(id),
    event_id INT REFERENCES event(id),
    PRIMARY KEY (id, event_id)
);

CREATE TABLE admin_action (
    id INT REFERENCES notification(id),
    admin_id INT REFERENCES admin(id),
    PRIMARY KEY (id, admin_id)
);

CREATE TABLE unblock_notification (
    id INT REFERENCES notification(id),
    admin_id INT REFERENCES admin(id),
    PRIMARY KEY (id, admin_id)
);

CREATE TABLE photo (
    id SERIAL PRIMARY KEY,
    comment_id INT REFERENCES comment(id),
    path VARCHAR(255) NOT NULL
);

-- Indexes

CREATE UNIQUE INDEX idx_user_username ON user_account(username);

CREATE INDEX idx_event_start_date ON event(start_date);

CREATE INDEX idx_event_category ON event(category_id); CLUSTER event USING idx_event_category;

-- Search

ALTER TABLE event ADD COLUMN ts_vector TSVECTOR;


UPDATE event
SET ts_vector = to_tsvector('portuguese', unaccent(name || ' ' || description || ' ' ||
                COALESCE((SELECT name FROM location WHERE location.id = event.location_id), '') || ' ' ||
                COALESCE((SELECT name FROM category WHERE category.id = event.category_id), '')
));

CREATE FUNCTION event_search_update() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.ts_vector := (
            setweight(to_tsvector('portuguese', unaccent(NEW.name)), 'A') ||
            setweight(to_tsvector('portuguese', unaccent(NEW.description)), 'B') ||
            setweight(to_tsvector('portuguese', unaccent(COALESCE((SELECT name FROM location WHERE location.id = NEW.location_id), ''))), 'C') ||
            setweight(to_tsvector('portuguese', unaccent(COALESCE((SELECT name FROM category WHERE category.id = NEW.category_id), ''))), 'C')
        );
    END IF;

    IF TG_OP = 'UPDATE' THEN
        IF (NEW.name <> OLD.name OR 
            NEW.description <> OLD.description OR 
            NEW.location_id <> OLD.location_id OR 
            NEW.category_id <> OLD.category_id) THEN

            NEW.ts_vector := (
                setweight(to_tsvector('portuguese', unaccent(NEW.name)), 'A') ||
                setweight(to_tsvector('portuguese', unaccent(NEW.description)), 'B') ||
                setweight(to_tsvector('portuguese', unaccent(COALESCE((SELECT name FROM location WHERE location.id = NEW.location_id), ''))), 'C') ||
                setweight(to_tsvector('portuguese', unaccent(COALESCE((SELECT name FROM category WHERE category.id = NEW.category_id), ''))), 'C')
            );
        END IF;
    END IF;
    RETURN NEW;
END $$ 
LANGUAGE plpgsql;


CREATE TRIGGER event_search_update
BEFORE INSERT OR UPDATE ON event
FOR EACH ROW EXECUTE FUNCTION event_search_update();

CREATE INDEX idx_event_full_text_search ON event USING GIN (ts_vector);

-- Triggers

CREATE FUNCTION prevent_duplicate_poll_response() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (
        SELECT 1
        FROM poll_response
        WHERE poll_id = NEW.poll_id
          AND user_id = NEW.user_id
    ) THEN
        RAISE EXCEPTION 'Duplicate poll response: The user has already responded to this poll.';
    END IF;
    
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER trigger_prevent_duplicate_poll_response
    BEFORE INSERT ON poll_response
    FOR EACH ROW
    EXECUTE FUNCTION prevent_duplicate_poll_response();

CREATE FUNCTION create_invite_notification() RETURNS TRIGGER AS
$BODY$
BEGIN
    INSERT INTO notification (emitted_by, emitted_to, date, viewed)
    VALUES (NEW.invite_by_id, NEW.invite_to_id, CURRENT_TIMESTAMP, FALSE);
    
    RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER trigger_create_invite_notification
    AFTER INSERT ON invite
    FOR EACH ROW
    EXECUTE FUNCTION create_invite_notification();

CREATE FUNCTION create_accepted_invite_notification() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF NEW.accepted = TRUE THEN
        INSERT INTO notification (emitted_by, emitted_to, date, viewed)
        VALUES (NEW.invite_to_id, NEW.invite_by_id, CURRENT_TIMESTAMP, FALSE);
    END IF;

    RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER trigger_accepted_invite_notification
    AFTER UPDATE ON invite
    FOR EACH ROW
    WHEN (NEW.accepted IS TRUE AND OLD.accepted IS DISTINCT FROM NEW.accepted)
    EXECUTE FUNCTION create_accepted_invite_notification();

CREATE FUNCTION create_event_update_notification() RETURNS TRIGGER AS
$BODY$
DECLARE
    notification_id INT;
    participant RECORD;
BEGIN
    FOR participant IN
        SELECT participator_id FROM participator WHERE event_id = NEW.id
    LOOP
        INSERT INTO notification (emitted_by, emitted_to, date, viewed)
        VALUES (NEW.creator_id, participant.participator_id, CURRENT_TIMESTAMP, FALSE)
        RETURNING id INTO notification_id;

        INSERT INTO event_update_notification (id, event_id)
        VALUES (notification_id, NEW.id);
    END LOOP;

    RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER trigger_event_update_notification
    AFTER UPDATE OF name, description, start_date, end_date, location_id, category_id ON event
    FOR EACH ROW
    EXECUTE FUNCTION create_event_update_notification();

CREATE FUNCTION create_comment_notification() RETURNS TRIGGER AS
$BODY$
DECLARE
    notification_id INT;
    participant RECORD;
BEGIN
    FOR participant IN
        SELECT participator_id FROM participator WHERE event_id = (SELECT event_id FROM comment WHERE id = NEW.id)
    LOOP
        INSERT INTO notification (emitted_by, emitted_to, date, viewed)
        VALUES (NEW.comment_by_id, participant.participator_id, CURRENT_TIMESTAMP, FALSE)
        RETURNING id INTO notification_id;

        INSERT INTO comment_notification (id, comment_id)
        VALUES (notification_id, NEW.id);
    END LOOP;

    RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER trigger_comment_notification
    AFTER INSERT ON comment
    FOR EACH ROW
    EXECUTE FUNCTION create_comment_notification();

CREATE FUNCTION notify_event_cancellation() RETURNS TRIGGER AS
$BODY$
DECLARE
    notification_id INT;
    participant RECORD;
BEGIN
    FOR participant IN
        SELECT participator_id FROM participator WHERE event_id = OLD.id
    LOOP
        INSERT INTO notification (emitted_by, emitted_to, date, viewed)
        VALUES (OLD.creator_id, participant.participator_id, CURRENT_TIMESTAMP, FALSE)
        RETURNING id INTO notification_id;

        INSERT INTO event_cancelation (id, event_id)
        VALUES (notification_id, OLD.id);
    END LOOP;

    RETURN OLD;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER trigger_event_cancellation
    AFTER UPDATE OF visibility ON event
    FOR EACH ROW
    WHEN (OLD.visibility IS DISTINCT FROM 'canceled' AND NEW.visibility = 'canceled')
    EXECUTE FUNCTION notify_event_cancellation();

CREATE FUNCTION create_poll_reminder() RETURNS TRIGGER AS
$BODY$
DECLARE
    notification_id INT;
    participant RECORD;
BEGIN
    FOR participant IN
        SELECT participator_id FROM participator WHERE event_id = NEW.event_id
    LOOP
        INSERT INTO notification (emitted_by, emitted_to, date, viewed)
        VALUES (NEW.event_id, participant.participator_id, CURRENT_TIMESTAMP, FALSE)
        RETURNING id INTO notification_id;

        INSERT INTO poll_reminder (id, poll_id)
        VALUES (notification_id, NEW.id);
    END LOOP;

    RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER trigger_poll_reminder
    AFTER INSERT ON poll
    FOR EACH ROW
    EXECUTE FUNCTION create_poll_reminder();

CREATE FUNCTION create_event_reminder_notification() RETURNS TRIGGER AS
$BODY$
DECLARE
    notification_id INT;
    participant RECORD;
    reminder_date TIMESTAMP;
BEGIN
    FOR participant IN
        SELECT participator_id FROM participator WHERE event_id = NEW.id
    LOOP
        IF NEW.start_date > CURRENT_DATE + INTERVAL '3 days' THEN
            reminder_date := NEW.start_date - INTERVAL '3 days';

            PERFORM pg_sleep(24 * 60 * 60 * 2); 

            INSERT INTO notification (emitted_by, emitted_to, date, viewed)
            VALUES (NEW.creator_id, participant.participator_id, reminder_date, FALSE)
            RETURNING id INTO notification_id;

            INSERT INTO event_reminder (id, event_id)
            VALUES (notification_id, NEW.id);
        END IF;

        IF NEW.start_date > CURRENT_DATE + INTERVAL '1 day' THEN
            reminder_date := NEW.start_date - INTERVAL '1 day';

            PERFORM pg_sleep(24 * 60 * 60); 

            INSERT INTO notification (emitted_by, emitted_to, date, viewed)
            VALUES (NEW.creator_id, participant.participator_id, reminder_date, FALSE)
            RETURNING id INTO notification_id;

            INSERT INTO event_reminder (id, event_id)
            VALUES (notification_id, NEW.id);
        END IF;
    END LOOP;

    RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER trigger_event_reminder_notification
    AFTER INSERT ON event
    FOR EACH ROW
    EXECUTE FUNCTION create_event_reminder_notification();

CREATE FUNCTION create_unblock_notification() RETURNS TRIGGER AS
$BODY$
DECLARE
    notification_id INT;
BEGIN
    IF (NEW.status IN ('approved', 'denied')) AND (OLD.status IS DISTINCT FROM NEW.status) THEN
        INSERT INTO notification (emitted_by, emitted_to, date, viewed)
        VALUES (NEW.decision_by_id, NEW.requested_by_id, CURRENT_TIMESTAMP, FALSE)
        RETURNING id INTO notification_id;

        INSERT INTO unblock_notification (id, admin_id)
        VALUES (notification_id, NEW.decision_by_id);
    END IF;

    RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER trigger_unblock_notification
    AFTER UPDATE ON unblock_request
    FOR EACH ROW
    WHEN (NEW.status IN ('approved', 'denied') AND OLD.status IS DISTINCT FROM NEW.status)
    EXECUTE FUNCTION create_unblock_notification();

/*
CREATE FUNCTION create_block_notification(admin_id INT) RETURNS TRIGGER AS
$BODY$
DECLARE
    notification_id INT;
BEGIN
    -- Check if the user was blocked in the update
    IF NEW.isBlocked = TRUE AND OLD.isBlocked = FALSE THEN
        -- Insert a new notification
        INSERT INTO notification (emitted_by, emitted_to, date, viewed)
        VALUES (NEW.id, NEW.id, CURRENT_TIMESTAMP, FALSE)
        RETURNING id INTO notification_id;

        -- Log the action in the admin_action table
        INSERT INTO admin_action (id, admin_id)
        VALUES (notification_id, admin_id);
    END IF;

    RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;


CREATE TRIGGER trigger_block_notification
    AFTER UPDATE OF isBlocked ON user_account
    FOR EACH ROW
    WHEN (OLD.isBlocked IS DISTINCT FROM NEW.isBlocked AND NEW.isBlocked = TRUE)
    EXECUTE FUNCTION create_block_notification();
*/

-- INSERT ------------------------------------------------------------------------------------------------------------

INSERT INTO user_account (name, username, email, password, isDeleted, isBlocked, profilePicture) VALUES
('Ana Silva', 'anasilva', 'ana.silva@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'ana.png'),
('João Pereira', 'joaopereira', 'joao.pereira@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'joao.png'),
('Maria Costa', 'mariacosta', 'maria.costa@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, TRUE, 'maria.png'),
('Pedro Sousa', 'pedrosousa', 'pedro.sousa@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', TRUE, FALSE, 'pedro.png');

INSERT INTO admin (email, password) VALUES
('admin1@example.com', '$2y$10$ii5KNGv4MCXCh20tghqKg./7AzxujQjCGIvcRe5CO1iXYIUK59CoS'),
('admin2@example.com', '$2y$10$IEkOO9H2AQe1WSf6qYZ5MeWBLzcvcOzh3koKw93wfQiuJBOvN9LmG');

INSERT INTO location (name) VALUES
('Centro Comunitário A'),
('Parque B'),
('Escola C'),
('Instituição D');

INSERT INTO category (name) VALUES
('Limpeza Ambiental'),
('Apoio a Idosos'),
('Distribuição de Alimentos'),
('Apoio Escolar');

INSERT INTO event (name, description, start_date, end_date, visibility, capacity, creator_id, location_id, category_id) VALUES
('Limpeza do Parque da Cidade', 'Evento para limpar o Parque da Cidade.', '2023-11-15 09:00:00', '2023-11-15 12:00:00', 'public', 50, 1, 2, 1),
('Distribuição de Refeições', 'Distribuição de refeições para pessoas em situação de rua.', '2023-11-20 18:00:00', '2023-11-20 21:00:00', 'public', 30, 2, 1, 3),
('Apoio Escolar', 'Aulas de reforço escolar para crianças de baixa renda.', '2023-12-01 14:00:00', '2023-12-01 17:00:00', 'private', 20, 1, 3, 4);

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

INSERT INTO comment (comment_by_id, event_id, text, is_pinned, score, edited, date) VALUES
(1, 1, 'This event looks amazing!', FALSE, 10, FALSE, '2023-11-15 09:00:00'),
(1, 1, 'This event looks like trash!', FALSE, 10, FALSE, '2023-11-15 09:01:00'),
(2, 1, 'Don’t miss this event!', TRUE, 20, FALSE, '2023-11-15 09:00:00'),
(3, 1, 'I can’t wait to attend!', FALSE, 0, FALSE, '2023-11-15 09:00:00'),
(4, 1, 'Looking forward to this!', FALSE, 5, TRUE, '2023-11-15 09:00:00'),
(2, 1, 'Great opportunity for networking.', FALSE, 15, FALSE, '2023-11-15 09:00:00');

INSERT INTO photo (comment_id, path) VALUES
(1, 'comment_pic_1.JPG'),
(1, 'comment_pic_2.JPG'),
(1, 'comment_pic_2.JPG'),
(1, 'comment_pic_2.JPG'),
(1, 'comment_pic_2.JPG'),
(1, 'comment_pic_2.JPG'),
(1, 'comment_pic_2.JPG'),
(1, 'comment_pic_2.JPG'),
(1, 'comment_pic_2.JPG'),
(1, 'comment_pic_2.JPG'),
(1, 'comment_pic_2.JPG'),
(1, 'comment_pic_2.JPG')