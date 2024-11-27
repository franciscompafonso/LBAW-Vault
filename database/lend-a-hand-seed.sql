DROP SCHEMA IF EXISTS lbaw2414 CASCADE;

CREATE SCHEMA lbaw2414;

SET search_path TO lbaw2414;

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
DROP TABLE IF EXISTS accepted_join_request_notification CASCADE;
DROP TABLE IF EXISTS join_request_notification CASCADE;
DROP TABLE IF EXISTS join_request CASCADE;
DROP TABLE IF EXISTS unblock_request CASCADE;
DROP TABLE IF EXISTS admin CASCADE;
DROP TABLE IF EXISTS location CASCADE;
DROP TABLE IF EXISTS category CASCADE;
DROP TABLE IF EXISTS notification CASCADE;
DROP TABLE IF EXISTS poll_response CASCADE;
DROP TABLE IF EXISTS poll CASCADE;
DROP TABLE IF EXISTS comment CASCADE;
DROP TABLE IF EXISTS answer_review CASCADE;
DROP TABLE IF EXISTS question_review CASCADE;
DROP TABLE IF EXISTS participator CASCADE;
DROP TABLE IF EXISTS event CASCADE;
DROP TABLE IF EXISTS user_account CASCADE;
DROP TABLE IF EXISTS photo CASCADE;
DROP TABLE IF EXISTS comment_feedback CASCADE;
DROP TABLE IF EXISTS password_reset_tokens CASCADE;

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
DROP TRIGGER IF EXISTS trigger_create_join_request_notification ON join_request;
DROP TRIGGER IF EXISTS trigger_accepted_join_request_notification ON join_request;
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
DROP FUNCTION IF EXISTS create_join_request_notification();
DROP FUNCTION IF EXISTS create_accepted_join_request_notification();
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
    visibility VARCHAR(50) NOT NULL,
    capacity INT,
    creator_id INT REFERENCES user_account(id) NOT NULL,
    location_id INT REFERENCES location(id) NOT NULL,
    category_id INT REFERENCES category(id) NOT NULL,
    banner VARCHAR(255) DEFAULT 'default-banner.png'
    CONSTRAINT ck_start_end CHECK (start_date < end_date)
);

CREATE TABLE participator (
    participator_id INT REFERENCES user_account(id),
    event_id INT REFERENCES event(id) ON DELETE CASCADE,
    PRIMARY KEY (participator_id, event_id)
);

CREATE TABLE question_review (
    id INT PRIMARY KEY,
    question_text VARCHAR(255) NOT NULL
);

CREATE TABLE answer_review (
    answered_by_id INT REFERENCES user_account(id),
    question_id INT NOT NULL REFERENCES question_review(id),
    about_event_id INT NOT NULL REFERENCES event(id) ON DELETE CASCADE,
    rating INT CHECK (rating >= 1 AND rating <= 5) NOT NULL,
    date DATE CHECK (date <= CURRENT_DATE) NOT NULL,
    PRIMARY KEY (answered_by_id, question_id, about_event_id)
);

CREATE TABLE comment (
    id SERIAL PRIMARY KEY,
    reply_to INT REFERENCES comment(id),
    comment_by_id INT REFERENCES user_account(id),
    event_id INT REFERENCES event(id) ON DELETE CASCADE,
    text TEXT NOT NULL,
    is_pinned BOOLEAN,
    score INT DEFAULT 0,
    edited BOOLEAN DEFAULT FALSE NOT NULL,
    date TIMESTAMP NOT NULL CHECK (date <= CURRENT_TIMESTAMP)
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
    event_id INT REFERENCES event(id),
    title VARCHAR(255) NOT NULL,
	accepted BOOLEAN,
    description TEXT NOT NULL,
    date TIMESTAMP NOT NULL CHECK (date <= CURRENT_TIMESTAMP)
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

CREATE TABLE join_request (
    id SERIAL PRIMARY KEY,
    join_request_by_id INT REFERENCES user_account(id),
    event_id INT REFERENCES event(id),
	accepted BOOLEAN,
    date TIMESTAMP NOT NULL CHECK (date <= CURRENT_TIMESTAMP)
);

CREATE TABLE join_request_notification (
    id INT REFERENCES notification(id),
    join_request_id INT REFERENCES join_request(id) ON DELETE CASCADE,
    PRIMARY KEY (id, join_request_id)
);

CREATE TABLE accepted_join_request_notification (
    id INT REFERENCES notification(id),
    join_request_id INT REFERENCES join_request(id) ON DELETE CASCADE,
    PRIMARY KEY (id, join_request_id)
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
    comment_id INT REFERENCES comment(id) ON DELETE CASCADE,
    path VARCHAR(255) NOT NULL
);

CREATE TABLE comment_feedback (
    user_id INT REFERENCES user_account(id),
    comment_id INT REFERENCES comment(id),
    feedback INT NOT NULL,
    PRIMARY KEY (user_id, comment_id)
);

CREATE TABLE password_reset_tokens (
    email VARCHAR(255) NOT NULL,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (email) REFERENCES user_account(email) ON DELETE CASCADE
);

-- Indexes

CREATE UNIQUE INDEX idx_user_username ON user_account(username);

CREATE INDEX idx_event_start_date ON event(start_date);

CREATE INDEX idx_event_category ON event(category_id); CLUSTER event USING idx_event_category;

-- Search

ALTER TABLE event ADD COLUMN ts_vector TSVECTOR;


UPDATE event
SET ts_vector = to_tsvector('english', name || ' ' || description || ' ' ||
                COALESCE((SELECT name FROM location WHERE location.id = event.location_id), '') || ' ' ||
                COALESCE((SELECT name FROM category WHERE category.id = event.category_id), '')
);

CREATE FUNCTION event_search_update() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.ts_vector := (
            setweight(to_tsvector('english', NEW.name), 'A') ||
            setweight(to_tsvector('english', NEW.description), 'B') ||
            setweight(to_tsvector('english', COALESCE((SELECT name FROM location WHERE location.id = NEW.location_id), '')), 'C') ||
            setweight(to_tsvector('english', COALESCE((SELECT name FROM category WHERE category.id = NEW.category_id), '')), 'C')
        );
    END IF;

    IF TG_OP = 'UPDATE' THEN
        IF (NEW.name <> OLD.name OR 
            NEW.description <> OLD.description OR 
            NEW.location_id <> OLD.location_id OR 
            NEW.category_id <> OLD.category_id) THEN

            NEW.ts_vector := (
                setweight(to_tsvector('english', NEW.name), 'A') ||
                setweight(to_tsvector('english', NEW.description), 'B') ||
                setweight(to_tsvector('english', COALESCE((SELECT name FROM location WHERE location.id = NEW.location_id), '')), 'C') ||
                setweight(to_tsvector('english', COALESCE((SELECT name FROM category WHERE category.id = NEW.category_id), '')), 'C')
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

CREATE OR REPLACE FUNCTION create_invite_notification() RETURNS TRIGGER AS
$BODY$
DECLARE
    notification_id INT;
BEGIN
    -- Insert a new notification and retrieve the generated ID
    INSERT INTO notification (emitted_by, emitted_to, date, viewed)
    VALUES (NEW.invite_by_id, NEW.invite_to_id, CURRENT_TIMESTAMP, FALSE)
    RETURNING id INTO notification_id;

    -- Insert the invite notification entry using the retrieved notification ID
    INSERT INTO invite_notification (id, invite_id)
    VALUES (notification_id, NEW.id);

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

CREATE OR REPLACE FUNCTION create_join_request_notification() RETURNS TRIGGER AS
$BODY$
DECLARE
    notification_id INT;
    join_request_to_id INT;
BEGIN
    -- Fetch the creator_id from the event table based on the event_id in the join_request
    SELECT creator_id INTO join_request_to_id
    FROM event
    WHERE id = NEW.event_id;

    -- Insert a new notification and retrieve the generated ID
    INSERT INTO notification (emitted_by, emitted_to, date, viewed)
    VALUES (NEW.join_request_by_id, join_request_to_id, CURRENT_TIMESTAMP, FALSE)
    RETURNING id INTO notification_id;

    -- Insert the join request notification entry using the retrieved notification ID
    INSERT INTO join_request_notification (id, join_request_id)
    VALUES (notification_id, NEW.id);

    RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER trigger_create_join_request_notification
    AFTER INSERT ON join_request
    FOR EACH ROW
    EXECUTE FUNCTION create_join_request_notification();

CREATE OR REPLACE FUNCTION create_accepted_join_request_notification() RETURNS TRIGGER AS
$BODY$
DECLARE
    notification_id INT;
    join_request_to_id INT;
BEGIN
    -- Fetch the creator_id from the event table based on the event_id in the join request
    SELECT creator_id INTO join_request_to_id
    FROM event
    WHERE id = NEW.event_id;

    -- Create a notification only if the join_request is accepted
    IF NEW.accepted = TRUE THEN
        -- Insert a new notification and retrieve the generated ID
        INSERT INTO notification (emitted_by, emitted_to, date, viewed)
        VALUES (join_request_to_id, NEW.join_request_by_id, CURRENT_TIMESTAMP, FALSE)
        RETURNING id INTO notification_id;

        -- Insert the accepted join_request notification entry using the retrieved notification ID
        INSERT INTO accepted_join_request_notification (id, join_request_id)
        VALUES (notification_id, NEW.id);
    END IF;

    RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER trigger_accepted_join_request_notification
    AFTER UPDATE ON join_request
    FOR EACH ROW
    WHEN (NEW.accepted IS TRUE AND OLD.accepted IS DISTINCT FROM NEW.accepted)
    EXECUTE FUNCTION create_accepted_join_request_notification();

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
('Pedro Sousa', 'pedrosousa', 'pedro.sousa@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', TRUE, FALSE, 'pedro.png'),
('Carlos Santos', 'carlossantos', 'carlos.santos@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Sofia Oliveira', 'sofiaoliveira', 'sofia.oliveira@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Ricardo Lima', 'ricardolima', 'ricardo.lima@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Beatriz Martins', 'beatrizmartins', 'beatriz.martins@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Miguel Ferreira', 'miguelferreira', 'miguel.ferreira@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Catarina Alves', 'catarinaalves', 'catarina.alves@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Diogo Santos', 'diogosantos', 'diogo.santos@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Inês Rodrigues', 'inesrodrigues', 'ines.rodrigues@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Tiago Fernandes', 'tiagofernandes', 'tiago.fernandes@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Mariana Costa', 'marianacosta', 'mariana.costa@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('André Silva', 'andresilva', 'andre.silva@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Carolina Pereira', 'carolinapereira', 'carolina.pereira@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Francisco Martins', 'franciscomartins', 'francisco.martins@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Teresa Santos', 'teresasantos', 'teresa.santos@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Paulo Ferreira', 'pauloferreira', 'paulo.ferreira@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Marta Oliveira', 'martaoliveira', 'marta.oliveira@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('António Costa', 'antoniocosta', 'antonio.costa@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Raquel Lima', 'raquellima', 'raquel.lima@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Hugo Alves', 'hugoalves', 'hugo.alves@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Rita Pereira', 'ritapereira', 'rita.pereira@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Daniel Santos', 'danielsantos', 'daniel.santos@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Filipa Costa', 'filipacosta', 'filipa.costa@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Gonçalo Silva', 'goncalosilva', 'goncalo.silva@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Sara Martins', 'saramartins', 'sara.martins@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Rui Ferreira', 'ruiferreira', 'rui.ferreira@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Joana Santos', 'joanasantos', 'joana.santos@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Bruno Oliveira', 'brunooliveira', 'bruno.oliveira@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Clara Pereira', 'clarapereira', 'clara.pereira@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('David Lima', 'davidlima', 'david.lima@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Eva Costa', 'evacosta', 'eva.costa@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Fernando Silva', 'fernandosilva', 'fernando.silva@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Gabriela Santos', 'gabrielasantos', 'gabriela.santos@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Henrique Martins', 'henriquemartins', 'henrique.martins@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Isabel Ferreira', 'isabelferreira', 'isabel.ferreira@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Jorge Alves', 'jorgealves', 'jorge.alves@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Laura Oliveira', 'lauraoliveira', 'laura.oliveira@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Manuel Costa', 'manuelcosta', 'manuel.costa@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Nadia Pereira', 'nadiapereira', 'nadia.pereira@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Patricia Lima', 'patricialima', 'patricia.lima@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Quinten Silva', 'quintensilva', 'quinten.silva@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Rosa Martins', 'rosamartins', 'rosa.martins@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Sergio Ferreira', 'sergioferreira', 'sergio.ferreira@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Tania Oliveira', 'taniaoliveira', 'tania.oliveira@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Umberto Costa', 'umbertocosta', 'umberto.costa@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Vera Alves', 'veraalves', 'vera.alves@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Xavier Santos', 'xaviersantos', 'xavier.santos@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Yara Pereira', 'yarapereira', 'yara.pereira@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Zeca Lima', 'zecalima', 'zeca.lima@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png'),
('Oscar Santos', 'oscarsantos', 'oscar.santos@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 'default-avatar.png');

INSERT INTO admin (email, password) VALUES
('admin1@example.com', '$2y$10$ii5KNGv4MCXCh20tghqKg./7AzxujQjCGIvcRe5CO1iXYIUK59CoS'),
('admin2@example.com', '$2y$10$IEkOO9H2AQe1WSf6qYZ5MeWBLzcvcOzh3koKw93wfQiuJBOvN9LmG');

INSERT INTO location (name) VALUES
('Cultural Center E'),
('Library F'),
('Hospital G'),
('Church H'),
('Association I'),
('Sports Center J'),
('Square K'),
('University L'),
('NGO M'),
('Social Center N'),
('Museum O'),
('Theater P'),
('Gymnasium Q'),
('Garden R'),
('Convention Center S');

INSERT INTO category (name) VALUES
('Health and Wellness'),
('Early Childhood Education'),
('Animal Protection'),
('Art and Culture'),
('Sports and Leisure'),
('Digital Inclusion'),
('Psychological Support'),
('Environmental Awareness'),
('Human Rights'),
('Refugee Support');

INSERT INTO event (name, description, start_date, end_date, visibility, capacity, creator_id, location_id, category_id) VALUES
('Blood Donation Campaign', 'Campaign to encourage blood donation.', '2023-12-01 09:00:00', '2023-12-01 17:00:00', 'public', 100, 1, 3, 1),
('Recycling Workshop', 'Learn recycling and sustainability techniques.', '2023-12-02 14:00:00', '2023-12-02 17:00:00', 'public', 50, 2, 1, 1),
('Yoga Class for Seniors', 'Yoga session adapted for the elderly.', '2023-12-03 10:00:00', '2023-12-03 11:30:00', 'public', 30, 3, 4, 5),
('Food Distribution', 'Distribution of basic food baskets for underprivileged families.', '2023-12-04 08:00:00', '2023-12-04 12:00:00', 'public', 200, 4, 5, 3),
('Tree Planting', 'Urban reforestation project.', '2023-12-05 07:00:00', '2023-12-05 12:00:00', 'public', 80, 5, 7, 1),
('Art Workshop for Children', 'Artistic activities for child development.', '2023-12-06 14:00:00', '2023-12-06 17:00:00', 'public', 40, 6, 2, 4),
('Mental Health Lecture', 'Discussion about psychological well-being.', '2023-12-07 19:00:00', '2023-12-07 21:00:00', 'public', 150, 7, 8, 7),
('Community Cleanup', 'Central square community cleaning.', '2023-12-08 08:00:00', '2023-12-08 12:00:00', 'public', 100, 8, 7, 1),
('Computer Classes', 'Basic computer course for seniors.', '2023-12-09 14:00:00', '2023-12-09 16:00:00', 'public', 30, 9, 2, 6),
('Vaccination Campaign', 'Free vaccination for pets.', '2023-12-10 09:00:00', '2023-12-10 15:00:00', 'public', 200, 10, 3, 3),
('Music Festival', 'Charitable musical performances.', '2023-12-11 18:00:00', '2023-12-11 22:00:00', 'public', 300, 11, 11, 4),
('Theater Workshop', 'Body expression workshop.', '2023-12-12 15:00:00', '2023-12-12 18:00:00', 'public', 50, 12, 12, 4),
('Sports Tournament', 'Friendly competition in various modalities.', '2023-12-13 09:00:00', '2023-12-13 17:00:00', 'public', 120, 13, 13, 5),
('Community Gardening', 'Community garden cultivation.', '2023-12-14 08:00:00', '2023-12-14 11:00:00', 'public', 40, 14, 14, 1),
('Motivational Talks', 'Series of talks on personal development.', '2023-12-15 19:00:00', '2023-12-15 22:00:00', 'public', 200, 15, 15, 7),
('Book Donation', 'Book collection campaign.', '2023-12-16 10:00:00', '2023-12-16 16:00:00', 'public', 150, 16, 2, 2),
('Adoption Fair', 'Responsible animal adoption.', '2023-12-17 09:00:00', '2023-12-17 15:00:00', 'public', 100, 17, 7, 3),
('Human Rights Seminar', 'Discussion and awareness about human rights.', '2023-12-18 10:00:00', '2023-12-18 14:00:00', 'public', 80, 18, 9, 9),
('Refugee Support Campaign', 'Collection of food and clothes for refugees.', '2023-12-19 08:00:00', '2023-12-19 17:00:00', 'public', 200, 19, 5, 10),
('First Aid Course', 'Basic first aid training.', '2023-12-18 14:00:00', '2023-12-18 18:00:00', 'public', 60, 18, 3, 1),
('Recycling Workshop', 'Reuse of recyclable materials.', '2023-12-19 15:00:00', '2023-12-19 17:00:00', 'public', 40, 19, 1, 1),
('Dance Class', 'Dance for the elderly.', '2023-12-20 10:00:00', '2023-12-20 11:30:00', 'public', 30, 20, 13, 5),
('Free Psychological Support', 'Community psychological care.', '2023-12-21 09:00:00', '2023-12-21 17:00:00', 'public', 40, 21, 4, 7),
('Craft Workshop', 'Learning artisan techniques.', '2023-12-22 14:00:00', '2023-12-22 17:00:00', 'public', 35, 22, 1, 4),
('Chess Tournament', 'Friendly chess competition.', '2023-12-23 13:00:00', '2023-12-23 18:00:00', 'public', 50, 23, 2, 5),
('Sustainability Lecture', 'Discussion about sustainable practices.', '2023-12-24 10:00:00', '2023-12-24 12:00:00', 'public', 120, 24, 8, 1),
('Cooking Course', 'Classes on full food utilization.', '2023-12-25 15:00:00', '2023-12-25 18:00:00', 'public', 30, 25, 5, 3),
('Clothing Donation Campaign', 'Clothing collection for underprivileged families.', '2023-12-26 09:00:00', '2023-12-26 16:00:00', 'public', 200, 26, 4, 9),
('Photography Workshop', 'Introduction to digital photography.', '2023-12-27 14:00:00', '2023-12-27 17:00:00', 'public', 25, 27, 11, 4),
('Preventive Health Action', 'Basic checkup and health guidance.', '2023-12-28 08:00:00', '2023-12-28 15:00:00', 'public', 150, 28, 3, 1),
('Meditation Class', 'Mindfulness practices.', '2023-12-29 19:00:00', '2023-12-29 20:30:00', 'public', 40, 29, 4, 7),
('Game Festival', 'Boardgame afternoon.', '2023-12-30 14:00:00', '2023-12-30 18:00:00', 'public', 80, 30, 2, 5),
('Creative Writing Workshop', 'Textual production workshop.', '2023-12-31 10:00:00', '2023-12-31 13:00:00', 'public', 35, 31, 2, 4),
('Gardening Class', 'Basic gardening techniques.', '2024-01-01 09:00:00', '2024-01-01 12:00:00', 'public', 30, 32, 14, 1),
('Language Course', 'Introduction to Spanish.', '2024-01-02 18:00:00', '2024-01-02 20:00:00', 'public', 40, 33, 8, 2),
('Sports Day', 'Various sporting activities.', '2024-01-03 08:00:00', '2024-01-03 17:00:00', 'public', 200, 34, 13, 5),
('Makeup Workshop', 'Basic makeup techniques.', '2024-01-04 15:00:00', '2024-01-04 18:00:00', 'public', 30, 35, 15, 4),
('Finance Lecture', 'Basic financial education.', '2024-01-05 19:00:00', '2024-01-05 21:00:00', 'public', 100, 36, 8, 6),
('Craft Fair', 'Exhibition and sale of local crafts.', '2024-01-06 10:00:00', '2024-01-06 18:00:00', 'public', 150, 37, 7, 4),
('Reading Group', 'Contemporary literature discussion.', '2024-01-07 14:00:00', '2024-01-07 16:00:00', 'public', 25, 38, 2, 4),
('Music Workshop', 'Introduction to musical instruments.', '2024-01-08 15:00:00', '2024-01-08 18:00:00', 'public', 20, 39, 11, 4),
('Health Day', 'Activities focused on health and well-being.', '2024-01-09 09:00:00', '2024-01-09 17:00:00', 'public', 300, 40, 3, 1),
('Private Nutrition Workshop', 'Guidance on healthy eating.', '2024-01-10 14:00:00', '2024-01-10 17:00:00', 'private', 50, 1, 3, 1),
('Science Fair', 'Exhibition of scientific projects.', '2024-01-11 09:00:00', '2024-01-11 18:00:00', 'public', 200, 2, 8, 2),
('Pet Meet-up', 'Socialization of pets.', '2024-01-12 10:00:00', '2024-01-12 15:00:00', 'public', 100, 3, 7, 3),
('Theater Festival', 'Community theater performances.', '2024-01-13 16:00:00', '2024-01-13 22:00:00', 'public', 150, 4, 12, 4),
('Volleyball Tournament', 'Amateur volleyball competition.', '2024-01-14 08:00:00', '2024-01-14 18:00:00', 'public', 80, 5, 13, 5),
('Excel Course', 'Basic Excel training.', '2024-01-15 14:00:00', '2024-01-15 17:00:00', 'public', 30, 6, 2, 6),
('Support Group', 'Emotional support meeting.', '2024-01-16 19:00:00', '2024-01-16 21:00:00', 'public', 25, 7, 4, 7),
('Environmental Action', 'Green area cleanup.', '2024-01-17 08:00:00', '2024-01-17 12:00:00', 'public', 100, 8, 14, 8),
('Consumer Rights', 'Lecture on basic rights.', '2024-01-18 19:00:00', '2024-01-18 21:00:00', 'public', 120, 9, 8, 9),
('Refugee Support', 'Support for refugees.', '2024-01-19 09:00:00', '2024-01-19 17:00:00', 'public', 50, 10, 5, 10),
('Health Checkup', 'Basic medical assessment.', '2024-01-20 08:00:00', '2024-01-20 16:00:00', 'public', 200, 11, 3, 1),
('Storytelling', 'Stories for children.', '2024-01-21 10:00:00', '2024-01-21 12:00:00', 'public', 40, 12, 2, 2),
('Free Neutering', 'Pet neutering program.', '2024-01-22 08:00:00', '2024-01-22 17:00:00', 'public', 50, 13, 3, 3),
('Art Exhibition', 'Local artists showcase.', '2024-01-23 10:00:00', '2024-01-23 20:00:00', 'public', 300, 14, 11, 4),
('Swimming Lesson', 'Introduction to swimming.', '2024-01-24 14:00:00', '2024-01-24 16:00:00', 'public', 20, 15, 13, 5),
('Basic Programming', 'Introduction to programming.', '2024-01-25 18:00:00', '2024-01-25 21:00:00', 'public', 25, 16, 8, 6),
('Group Therapy', 'Collective therapy session.', '2024-01-26 19:00:00', '2024-01-26 21:00:00', 'public', 15, 17, 4, 7),
('Seedling Planting', 'Urban reforestation.', '2024-01-27 07:00:00', '2024-01-27 11:00:00', 'public', 80, 18, 14, 8),
('Human Rights Lecture', 'Discussion on fundamental rights.', '2024-01-28 19:00:00', '2024-01-28 21:00:00', 'public', 150, 19, 8, 9),
('Portuguese Course', 'Classes for refugees.', '2024-01-29 14:00:00', '2024-01-29 16:00:00', 'public', 30, 20, 2, 10),
('Gymnastics for All', 'Adapted exercises.', '2024-01-30 09:00:00', '2024-01-30 10:30:00', 'public', 40, 21, 13, 1),
('Art for Babies', 'Sensory activities.', '2024-01-31 10:00:00', '2024-01-31 11:00:00', 'public', 15, 22, 1, 2),
('Adoption Fair', 'Responsible adoption.', '2024-02-01 09:00:00', '2024-02-01 16:00:00', 'public', 100, 23, 7, 3),
('Benefit Concert', 'Classical music.', '2024-02-02 20:00:00', '2024-02-02 22:00:00', 'public', 200, 24, 12, 4),
('Community Football', 'Amateur tournament.', '2024-02-03 08:00:00', '2024-02-03 18:00:00', 'public', 120, 25, 13, 5),
('Digital Security', 'Lecture on cybersecurity.', '2024-02-04 19:00:00', '2024-02-04 21:00:00', 'public', 80, 26, 8, 6),
('Guided Meditation', 'Meditative practices.', '2024-02-05 07:00:00', '2024-02-05 08:00:00', 'public', 30, 27, 4, 7),
('Creative Recycling', 'Art with recycled materials.', '2024-02-06 14:00:00', '2024-02-06 17:00:00', 'public', 35, 28, 1, 8),
('Legal Advice', 'Free legal guidance.', '2024-02-07 09:00:00', '2024-02-07 17:00:00', 'public', 40, 29, 5, 9),
('Cultural Integration', 'Multicultural meeting.', '2024-02-08 15:00:00', '2024-02-08 19:00:00', 'public', 100, 30, 15, 10),
('Disease Prevention', 'Lecture on preventive health.', '2024-02-09 19:00:00', '2024-02-09 21:00:00', 'public', 150, 31, 3, 1),
('Early Childhood Music', 'Introduction to music.', '2024-02-10 10:00:00', '2024-02-10 11:30:00', 'public', 20, 32, 11, 2),
('Pet Care', 'Veterinary guidance.', '2024-02-11 14:00:00', '2024-02-11 17:00:00', 'public', 60, 33, 3, 3),
('Contemporary Dance', 'Dance workshop.', '2024-02-12 18:00:00', '2024-02-12 20:00:00', 'public', 25, 34, 12, 4),
('Basketball for Beginners', 'Introductory class.', '2024-02-13 16:00:00', '2024-02-13 18:00:00', 'public', 20, 35, 13, 5),
('Social Media', 'Professional use of media.', '2024-02-14 19:00:00', '2024-02-14 21:00:00', 'public', 40, 36, 8, 6),
('Self-Knowledge', 'Personal development workshop.', '2024-02-15 18:00:00', '2024-02-15 21:00:00', 'public', 30, 37, 4, 7),
('Urban Garden', 'Cultivation in small spaces.', '2024-02-16 09:00:00', '2024-02-16 12:00:00', 'public', 25, 38, 14, 8),
('Active Citizenship', 'Rights and duties.', '2024-02-17 19:00:00', '2024-02-17 21:00:00', 'public', 100, 39, 8, 9),
('Educational Support', 'Support for refugees.', '2024-02-18 14:00:00', '2024-02-18 17:00:00', 'public', 30, 40, 2, 10),
('Request only Outdoor Yoga', 'Morning practice.', '2024-02-19 07:00:00', '2024-02-19 08:30:00', 'request_only', 50, 1, 7, 1),
('Educational Games', 'Pedagogical activities.', '2024-02-20 14:00:00', '2024-02-20 16:00:00', 'public', 30, 2, 1, 2),
('Puppy Fair', 'Adoption of puppies.', '2024-02-21 10:00:00', '2024-02-21 16:00:00', 'public', 80, 3, 7, 3),
('Outdoor Cinema', 'Movie screening.', '2024-02-22 19:00:00', '2024-02-22 22:00:00', 'public', 200, 4, 7, 4),
('Charity Run', 'Beneficent event.', '2024-02-23 07:00:00', '2024-02-23 10:00:00', 'public', 300, 5, 13, 5),
('Safe Internet', 'Conscious browsing.', '2024-02-24 15:00:00', '2024-02-24 17:00:00', 'public', 40, 6, 8, 6),
('Art Therapy', 'Therapy through art.', '2024-02-25 14:00:00', '2024-02-25 16:00:00', 'public', 20, 7, 1, 7),
('Composting', 'Composting techniques.', '2024-02-26 09:00:00', '2024-02-26 11:00:00', 'public', 30, 8, 14, 8),
('Labor Rights', 'Legal guidance.', '2024-02-27 19:00:00', '2024-02-27 21:00:00', 'public', 100, 9, 8, 9),
('Language Course', 'English for refugees.', '2024-02-28 18:00:00', '2024-02-28 20:00:00', 'public', 25, 10, 2, 10),
('Physical Assessment', 'Measurements and guidance.', '2024-02-29 08:00:00', '2024-02-29 17:00:00', 'public', 100, 11, 13, 1),
('Painting Workshop', 'Art for children.', '2024-03-01 14:00:00', '2024-03-01 16:00:00', 'public', 25, 12, 1, 2),
('Microchipping', 'Pet identification.', '2024-03-02 09:00:00', '2024-03-02 15:00:00', 'public', 100, 13, 3, 3),
('Talent Show', 'Artistic performances.', '2024-03-03 18:00:00', '2024-03-03 22:00:00', 'public', 200, 14, 12, 4),
('Skating', 'Trial lesson.', '2024-03-04 15:00:00', '2024-03-04 17:00:00', 'public', 30, 15, 13, 5),
('Basic Word', 'Introduction to Word.', '2024-03-05 14:00:00', '2024-03-05 16:00:00', 'public', 25, 16, 8, 6),
('Discussion Circle', 'Debate on mental health.', '2024-03-06 19:00:00', '2024-03-06 21:00:00', 'public', 30, 17, 4, 7),
('Selective Collection', 'Recycling guidelines.', '2024-03-07 10:00:00', '2024-03-07 12:00:00', 'public', 50, 18, 1, 8),
('Women''s Rights', 'Informative lecture.', '2024-03-08 19:00:00', '2024-03-08 21:00:00', 'public', 150, 19, 8, 9),
('Welcoming', 'Support for immigrants.', '2024-03-09 09:00:00', '2024-03-09 17:00:00', 'public', 40, 20, 5, 10),
('Tai Chi', 'Practices for seniors.', '2024-03-10 08:00:00', '2024-03-10 09:30:00', 'public', 30, 21, 13, 1),
('Youth Robotics', 'Introductory robotics workshop.', '2024-05-15 14:00:00', '2024-05-15 17:00:00', 'public', 40, 41, 8, 2),
('Sustainable Community Garden', 'Urban agriculture project.', '2024-04-01 08:00:00', '2025-12-31 18:00:00', 'public', 100, 42, 14, 1),
('Social Innovation Hackathon', 'Social solutions development marathon.', '2024-06-20 09:00:00', '2024-06-22 18:00:00', 'public', 200, 43, 11, 6),
('Libras Course', 'Brazilian Sign Language classes.', '2024-05-01 19:00:00', '2024-07-30 21:00:00', 'public', 30, 44, 2, 2),
('Maker Culture Festival', 'Exhibition of creativity and technology projects.', '2024-07-10 10:00:00', '2024-07-14 22:00:00', 'public', 500, 45, 12, 4),
('Math Olympiad', 'Competition for students.', '2024-08-15 09:00:00', '2024-08-17 16:00:00', 'public', 150, 46, 8, 2),
('Environmental Expedition', 'Ecosystem research and preservation.', '2024-06-05 06:00:00', '2024-06-09 18:00:00', 'public', 50, 47, 14, 8),
('Urban Restoration Task Force', 'Public space revitalization project.', '2024-04-15 07:00:00', '2025-04-15 18:00:00', 'public', 200, 48, 7, 1),
('Accessible Cinema', 'Screenings with audio description and subtitles.', '2024-05-20 18:00:00', '2024-12-20 22:00:00', 'public', 100, 49, 11, 4),
('Technological Mentorship Program', 'Support for young tech entrepreneurs.', '2024-03-01 10:00:00', '2025-03-01 17:00:00', 'public', 50, 50, 8, 6),
('Inclusive Dance', 'Dance lessons for people with disabilities.', '2024-06-01 15:00:00', '2024-08-31 17:00:00', 'public', 30, 51, 12, 5),
('Social Entrepreneurship Fair', 'Exhibition of social impact projects.', '2024-09-05 10:00:00', '2024-09-07 19:00:00', 'public', 250, 52, 7, 6),
('Documentary Photography Workshop', 'Techniques for visual narratives.', '2024-07-22 14:00:00', '2024-07-24 18:00:00', 'public', 25, 53, 11, 4),
('Continuous Mental Health Campaign', 'Psychological support program.', '2024-01-01 09:00:00', '2025-12-31 17:00:00', 'public', 100, 1, 4, 7),
('3D Printing Lab', 'Prototyping and digital manufacturing course.', '2024-08-01 18:00:00', '2024-10-31 21:00:00', 'public', 20, 3, 8, 6),
('Urban Archeology Project', 'Historical research and preservation.', '2024-06-15 08:00:00', '2024-07-15 17:00:00', 'public', 40, 5, 1, 2),
('Astronomy Lecture Series', 'Scientific discussion series.', '2024-05-10 19:00:00', '2024-06-10 21:00:00', 'public', 30, 7, 13, 8),
('Street Theater Festival', 'Artistic performances in public spaces', '2024-07-01 18:00:00', '2024-07-07 22:00:00', 'public', 300, 38, 12, 4),
('Urban Farming Program', 'Community cultivation in urban areas', '2024-04-01 07:00:00', '2025-03-31 18:00:00', 'public', 80, 49, 14, 1),
('Programming Bootcamp', 'Intensive training in software development', '2024-06-10 09:00:00', '2024-07-10 17:00:00', 'public', 20, 11, 8, 6),
('Tech Summit 2025', 'International technology conference', '2025-03-15 09:00:00', '2025-03-17 18:00:00', 'public', 500, 11, 11, 6),
('Global Climate Action Workshop', 'Sustainable development strategies', '2025-01-20 10:00:00', '2025-01-22 16:00:00', 'public', 250, 22, 14, 8),
('International Music Festival', 'Multicultural musical performances', '2025-06-01 17:00:00', '2025-06-07 23:00:00', 'public', 1000, 43, 12, 4),
('Robotics and AI Symposium', 'Future of artificial intelligence', '2025-04-10 13:00:00', '2025-04-12 20:00:00', 'public', 200, 34, 8, 6),
('Urban Farming Innovation Expo', 'Sustainable agriculture technologies', '2025-02-05 11:00:00', '2025-02-07 19:00:00', 'public', 300, 45, 14, 1),
('Mental Health Awareness Conference', 'Global mental wellness strategies', '2025-05-12 09:00:00', '2025-05-14 17:00:00', 'public', 400, 46, 4, 7),
('International Film Festival', 'Independent cinema showcase', '2025-07-15 18:00:00', '2025-07-22 22:00:00', 'public', 600, 27, 11, 4),
('Renewable Energy Summit', 'Future of sustainable energy', '2025-09-10 10:00:00', '2025-09-12 18:00:00', 'public', 350, 48, 8, 8),
('Cultural Heritage Preservation Forum', 'Global conservation strategies', '2025-03-25 14:00:00', '2025-03-27 20:00:00', 'public', 200, 49, 1, 2),
('Startup Innovation Challenge', 'International entrepreneurship competition', '2025-05-20 09:00:00', '2025-05-23 18:00:00', 'public', 250, 10, 11, 6),
('Global Education Technology Conference', 'Future of learning technologies', '2025-02-15 10:00:00', '2025-02-17 16:00:00', 'public', 300, 11, 8, 2),
('Biodiversity Conservation Workshop', 'Environmental protection strategies', '2025-06-25 08:00:00', '2025-06-27 17:00:00', 'public', 150, 22, 14, 8),
('International Dance Festival', 'Global dance performance showcase', '2025-08-05 19:00:00', '2025-08-10 23:00:00', 'public', 500, 43, 12, 5),
('Digital Privacy and Security Symposium', 'Cybersecurity innovations', '2025-04-22 13:00:00', '2025-04-24 20:00:00', 'public', 200, 24, 8, 6),
('Social Innovation Challenge', 'Global impact entrepreneurship', '2025-03-05 10:00:00', '2025-03-07 18:00:00', 'public', 250, 1, 11, 6),
('World Blockchain Congress', 'Global blockchain and cryptocurrency summit', '2025-10-15 09:00:00', '2025-10-17 18:00:00', 'public', 400, 1, 11, 6),
('International Ocean Conservation Summit', 'Marine ecosystem protection strategies', '2025-11-05 10:00:00', '2025-11-07 16:00:00', 'public', 250, 2, 14, 8),
('Global Health Innovation Forum', 'Future of medical technology and healthcare', '2025-12-10 13:00:00', '2025-12-12 20:00:00', 'public', 300, 2, 4, 7),
('Virtual Reality Technology Expo', 'Cutting-edge VR and AR technologies', '2026-01-20 11:00:00', '2026-01-22 19:00:00', 'public', 200, 2, 8, 6),
('International Archaeological Conference', 'Global heritage and cultural preservation', '2026-02-15 09:00:00', '2026-02-17 17:00:00', 'public', 150, 3, 1, 2),
('Sustainable Urban Design Symposium', 'Future of city planning and infrastructure', '2026-03-25 14:00:00', '2026-03-27 20:00:00', 'public', 250, 3, 14, 8),
('Global AI Ethics Forum', 'Ethical considerations in artificial intelligence', '2026-04-10 10:00:00', '2026-04-12 16:00:00', 'public', 200, 3, 8, 6),
('International Space Exploration Summit', 'Future of space technology and research', '2026-05-20 09:00:00', '2026-05-22 18:00:00', 'public', 300, 43, 11, 2),
('World Cultural Arts Festival', 'Global performing and visual arts showcase', '2026-06-15 18:00:00', '2026-06-21 22:00:00', 'public', 500, 34, 12, 4),
('Climate Adaptation Conference', 'Strategies for environmental resilience', '2026-07-10 13:00:00', '2026-07-12 20:00:00', 'public', 250, 25, 14, 8),
('International Quantum Computing Symposium', 'Advances in quantum technology', '2026-08-05 11:00:00', '2026-08-07 19:00:00', 'public', 200, 46, 8, 6),
('Global Food Security Workshop', 'Sustainable agriculture and nutrition', '2026-09-15 10:00:00', '2026-09-17 16:00:00', 'public', 300, 47, 14, 1),
('International Disability Rights Conference', 'Inclusive social policies and technologies', '2026-10-20 09:00:00', '2026-10-22 17:00:00', 'public', 250, 18, 4, 9),
('World Renewable Energy Congress', 'Future of clean energy technologies', '2026-11-10 14:00:00', '2026-11-12 20:00:00', 'public', 350, 19, 8, 8),
('Global Artificial Intelligence for Good Summit', 'AI solutions for social challenges', '2026-12-05 10:00:00', '2026-12-07 16:00:00', 'public', 300, 40, 11, 6),
('International Marine Biology Symposium', 'Ocean ecosystem research and conservation', '2027-01-15 09:00:00', '2027-01-17 18:00:00', 'public', 200, 21, 14, 8),
('World Design Innovation Forum', 'Future of design and creative technologies', '2027-02-20 13:00:00', '2027-02-22 20:00:00', 'public', 250, 42, 11, 4),
('Global Mental Health and Technology Conference', 'Digital solutions for psychological wellness', '2027-03-10 11:00:00', '2027-03-12 19:00:00', 'public', 300, 13, 4, 7),
('International Robotics and Automation Expo', 'Future of intelligent machines', '2027-04-15 10:00:00', '2027-04-17 16:00:00', 'public', 400, 24, 8, 6),
('World Indigenous Knowledge Symposium', 'Preserving traditional wisdom and practices', '2027-05-25 14:00:00', '2027-05-27 20:00:00', 'public', 150, 45, 1, 2),
('Global Education Innovation Summit', 'Future of learning and educational technologies', '2027-06-10 09:00:00', '2027-06-12 18:00:00', 'public', 300, 36, 8, 2),
('International Neuroscience and Technology Conference', 'Brain research and cognitive technologies', '2027-07-20 13:00:00', '2027-07-22 20:00:00', 'public', 250, 37, 4, 7),
('World Sustainable Tourism Forum', 'Eco-friendly travel and cultural exchange', '2027-08-05 11:00:00', '2027-08-07 19:00:00', 'public', 200, 38, 14, 8),
('Global Precision Medicine Symposium', 'Personalized healthcare technologies', '2027-09-15 10:00:00', '2027-09-17 16:00:00', 'public', 300, 19, 4, 7),
('International Circular Economy Summit', 'Sustainable resource management', '2027-10-20 14:00:00', '2027-10-22 20:00:00', 'public', 250, 40, 14, 8),
('World Youth Innovation Challenge', 'Global youth-led solutions for social issues', '2027-11-10 09:00:00', '2027-11-12 18:00:00', 'public', 40, 11, 11, 6),
('Global Artificial Ecosystem Symposium', 'Technology and nature conservation', '2027-12-05 13:00:00', '2027-12-07 20:00:00', 'public', 200, 32, 14, 8),
('International Digital Art and Culture Festival', 'Emerging creative technologies', '2028-01-15 18:00:00', '2028-01-21 22:00:00', 'public', 500, 13, 12, 4),
('World Humanitarian Technology Conference', 'Tech solutions for global challenges', '2028-02-20 10:00:00', '2028-02-22 16:00:00', 'public', 300, 14, 11, 6),
('Global Indigenous Technology Summit', 'Bridging traditional knowledge and innovation', '2028-03-10 14:00:00', '2028-03-12 20:00:00', 'public', 150, 5, 1, 2);

INSERT INTO participator (participator_id, event_id) VALUES
-- Events 1-20
(1, 1), (5, 1), (12, 1), (23, 1), (35, 1), (42, 1), (48, 1),
(3, 2), (7, 2), (15, 2), (25, 2), (31, 2), (44, 2), (50, 2), (53, 2),
(2, 3), (8, 3), (17, 3), (28, 3), (33, 3), (41, 3),
(4, 4), (11, 4), (19, 4), (27, 4), (36, 4), (45, 4), (51, 4),
(6, 5), (13, 5), (22, 5), (29, 5), (37, 5), (46, 5),
(9, 6), (16, 6), (24, 6), (32, 6), (39, 6), (47, 6), (52, 6),
(1, 7), (10, 7), (18, 7), (26, 7), (38, 7), (43, 7), (49, 7),
(5, 8), (14, 8), (21, 8), (30, 8), (40, 8), (48, 8),
(3, 9), (12, 9), (23, 9), (34, 9), (42, 9), (50, 9), (53, 9),
(7, 10), (15, 10), (25, 10), (35, 10), (44, 10), (51, 10),
-- Events 11-30
(2, 11), (11, 11), (20, 11), (31, 11), (41, 11), (49, 11),
(4, 12), (13, 12), (24, 12), (36, 12), (45, 12), (52, 12),
(6, 13), (16, 13), (27, 13), (37, 13), (46, 13), (53, 13),
(8, 14), (17, 14), (28, 14), (38, 14), (47, 14),
(1, 15), (9, 15), (19, 15), (29, 15), (39, 15), (48, 15),
(3, 16), (12, 16), (22, 16), (32, 16), (43, 16), (50, 16),
(5, 17), (14, 17), (25, 17), (35, 17), (44, 17), (51, 17),
(7, 18), (16, 18), (26, 18), (36, 18), (45, 18), (52, 18),
(2, 19), (10, 19), (21, 19), (33, 19), (42, 19), (49, 19),
(4, 20), (13, 20), (23, 20), (34, 20), (46, 20), (53, 20),
-- Events 21-40
(6, 21), (15, 21), (24, 21), (37, 21), (47, 21),
(8, 22), (17, 22), (27, 22), (38, 22), (48, 22), (51, 22),
(1, 23), (11, 23), (20, 23), (31, 23), (41, 23), (50, 23),
(3, 24), (14, 24), (25, 24), (35, 24), (44, 24), (52, 24),
(5, 25), (16, 25), (26, 25), (36, 25), (45, 25), (53, 25),
(7, 26), (18, 26), (28, 26), (39, 26), (46, 26),
(2, 27), (12, 27), (22, 27), (32, 27), (42, 27), (49, 27),
(4, 28), (15, 28), (24, 28), (34, 28), (43, 28), (51, 28),
(6, 29), (17, 29), (27, 29), (37, 29), (47, 29), (52, 29),
(8, 30), (19, 30), (29, 30), (38, 30), (48, 30), (53, 30),
-- Events 31-50
(1, 31), (13, 31), (23, 31), (33, 31), (44, 31), (50, 31),
(3, 32), (16, 32), (25, 32), (35, 32), (45, 32), (51, 32),
(5, 33), (18, 33), (28, 33), (36, 33), (46, 33), (52, 33),
(7, 34), (14, 34), (24, 34), (34, 34), (43, 34), (49, 34),
(2, 35), (12, 35), (22, 35), (32, 35), (42, 35), (53, 35),
(4, 36), (15, 36), (26, 36), (37, 36), (47, 36), (50, 36),
(6, 37), (17, 37), (28, 37), (38, 37), (48, 37), (51, 37),
(8, 38), (19, 38), (29, 38), (39, 38), (46, 38), (52, 38),
(1, 39), (11, 39), (21, 39), (31, 39), (41, 39), (49, 39),
(3, 40), (13, 40), (23, 40), (33, 40), (43, 40), (53, 40),
-- Events 41-60
(5, 41), (15, 41), (25, 41), (35, 41), (45, 41), (50, 41),
(7, 42), (17, 42), (27, 42), (37, 42), (47, 42), (51, 42),
(2, 43), (12, 43), (22, 43), (32, 43), (42, 43), (52, 43),
(4, 44), (14, 44), (24, 44), (34, 44), (44, 44), (49, 44),
(6, 45), (16, 45), (26, 45), (36, 45), (46, 45), (53, 45),
(8, 46), (18, 46), (28, 46), (38, 46), (48, 46), (50, 46),
(1, 47), (11, 47), (21, 47), (31, 47), (41, 47), (51, 47),
(3, 48), (13, 48), (23, 48), (33, 48), (43, 48), (52, 48),
(5, 49), (15, 49), (25, 49), (35, 49), (45, 49), (49, 49),
(7, 50), (17, 50), (27, 50), (37, 50), (47, 50), (53, 50),
-- Events 51-70
(2, 51), (12, 51), (22, 51), (32, 51), (42, 51), (50, 51),
(4, 52), (14, 52), (24, 52), (34, 52), (44, 52), (51, 52),
(6, 53), (16, 53), (26, 53), (36, 53), (46, 53), (52, 53),
(8, 54), (18, 54), (28, 54), (38, 54), (48, 54), (53, 54),
(1, 55), (11, 55), (21, 55), (31, 55), (41, 55), (49, 55),
(3, 56), (13, 56), (23, 56), (33, 56), (43, 56), (50, 56),
(5, 57), (15, 57), (25, 57), (35, 57), (45, 57), (51, 57),
(7, 58), (17, 58), (27, 58), (37, 58), (47, 58), (52, 58),
(2, 59), (12, 59), (22, 59), (32, 59), (42, 59), (53, 59),
(4, 60), (14, 60), (24, 60), (34, 60), (44, 60), (49, 60),
-- Events 61-80
(6, 61), (16, 61), (26, 61), (36, 61), (46, 61), (50, 61),
(8, 62), (18, 62), (28, 62), (38, 62), (48, 62), (51, 62),
(1, 63), (11, 63), (21, 63), (31, 63), (41, 63), (52, 63),
(3, 64), (13, 64), (23, 64), (33, 64), (43, 64), (53, 64),
(5, 65), (15, 65), (25, 65), (35, 65), (45, 65), (49, 65),
(7, 66), (17, 66), (27, 66), (37, 66), (47, 66), (50, 66),
(2, 67), (12, 67), (22, 67), (32, 67), (42, 67), (51, 67),
(4, 68), (14, 68), (24, 68), (34, 68), (44, 68), (52, 68),
(6, 69), (16, 69), (26, 69), (36, 69), (46, 69), (53, 69),
(8, 70), (18, 70), (28, 70), (38, 70), (48, 70), (49, 70),
-- Events 71-90
(1, 71), (11, 71), (21, 71), (31, 71), (41, 71), (50, 71),
(3, 72), (13, 72), (23, 72), (33, 72), (43, 72), (51, 72),
(5, 73), (15, 73), (25, 73), (35, 73), (45, 73), (52, 73),
(7, 74), (17, 74), (27, 74), (37, 74), (47, 74), (53, 74),
(2, 75), (12, 75), (22, 75), (32, 75), (42, 75), (49, 75),
(4, 76), (14, 76), (24, 76), (34, 76), (44, 76), (50, 76),
(6, 77), (16, 77), (26, 77), (36, 77), (46, 77), (51, 77),
(8, 78), (18, 78), (28, 78), (38, 78), (48, 78), (52, 78),
(1, 79), (11, 79), (21, 79), (31, 79), (41, 79), (53, 79),
(3, 80), (13, 80), (23, 80), (33, 80), (43, 80), (49, 80),
-- Events 81-103
(5, 81), (15, 81), (25, 81), (35, 81), (45, 81), (50, 81),
(7, 82), (17, 82), (27, 82), (37, 82), (47, 82), (51, 82),
(2, 83), (12, 83), (22, 83), (32, 83), (42, 83), (52, 83),
(4, 84), (14, 84), (24, 84), (34, 84), (44, 84), (53, 84),
(6, 85), (16, 85), (26, 85), (36, 85), (46, 85), (49, 85),
(8, 86), (18, 86), (28, 86), (38, 86), (48, 86), (50, 86),
(1, 87), (11, 87), (21, 87), (31, 87), (41, 87), (51, 87),
(3, 88), (13, 88), (23, 88), (33, 88), (43, 88), (52, 88),
(5, 89), (15, 89), (25, 89), (35, 89), (45, 89), (53, 89),
(7, 90), (17, 90), (27, 90), (37, 90), (47, 90), (49, 90),
(2, 91), (12, 91), (22, 91), (32, 91), (42, 91), (50, 91),
(4, 92), (14, 92), (24, 92), (34, 92), (44, 92), (51, 92),
(6, 93), (16, 93), (26, 93), (36, 93), (46, 93), (52, 93),
(8, 94), (18, 94), (28, 94), (38, 94), (48, 94), (53, 94),
(1, 95), (11, 95), (21, 95), (31, 95), (41, 95), (49, 95),
(3, 96), (13, 96), (23, 96), (33, 96), (43, 96), (50, 96),
(5, 97), (15, 97), (25, 97), (35, 97), (45, 97), (51, 97),
(7, 98), (17, 98), (27, 98), (37, 98), (47, 98), (52, 98),
(2, 99), (12, 99), (22, 99), (32, 99), (42, 99), (53, 99),
(4, 100), (14, 100), (24, 100), (34, 100), (44, 100), (49, 100),
(6, 101), (16, 101), (26, 101), (36, 101), (46, 101), (50, 101),
(8, 102), (18, 102), (28, 102), (38, 102), (48, 102), (51, 102),
(1, 103), (11, 103), (21, 103), (31, 103), (41, 103), (52, 103),
-- Events 104-120
(3, 104), (13, 104), (23, 104), (33, 104), (43, 104), (53, 104),
(5, 105), (15, 105), (25, 105), (35, 105), (45, 105), (49, 105),
(7, 106), (17, 106), (27, 106), (37, 106), (47, 106), (50, 106),
(2, 107), (12, 107), (22, 107), (32, 107), (42, 107), (51, 107),
(4, 108), (14, 108), (24, 108), (34, 108), (44, 108), (52, 108),
(6, 109), (16, 109), (26, 109), (36, 109), (46, 109), (53, 109),
(8, 110), (18, 110), (28, 110), (38, 110), (48, 110), (49, 110),
(1, 111), (11, 111), (21, 111), (31, 111), (41, 111), (50, 111),
(3, 112), (13, 112), (23, 112), (33, 112), (43, 112), (51, 112),
(5, 113), (15, 113), (25, 113), (35, 113), (45, 113), (52, 113),
(7, 114), (17, 114), (27, 114), (37, 114), (47, 114), (53, 114),
(2, 115), (12, 115), (22, 115), (32, 115), (42, 115), (49, 115),
(4, 116), (14, 116), (24, 116), (34, 116), (44, 116), (50, 116),
(6, 117), (16, 117), (26, 117), (36, 117), (46, 117), (51, 117),
(8, 118), (18, 118), (28, 118), (38, 118), (48, 118), (52, 118),
(1, 119), (11, 119), (21, 119), (31, 119), (41, 119), (53, 119),
(3, 120), (13, 120), (23, 120), (33, 120), (43, 120), (49, 120),
-- Events 121-140
(5, 121), (15, 121), (25, 121), (35, 121), (45, 121), (50, 121),
(7, 122), (17, 122), (27, 122), (37, 122), (47, 122), (51, 122),
(2, 123), (12, 123), (22, 123), (32, 123), (42, 123), (52, 123),
(4, 124), (14, 124), (24, 124), (34, 124), (44, 124), (53, 124),
(6, 125), (16, 125), (26, 125), (36, 125), (46, 125), (49, 125),
(8, 126), (18, 126), (28, 126), (38, 126), (48, 126), (50, 126),
(1, 127), (11, 127), (21, 127), (31, 127), (41, 127), (51, 127),
(3, 128), (13, 128), (23, 128), (33, 128), (43, 128), (52, 128),
(5, 129), (15, 129), (25, 129), (35, 129), (45, 129), (53, 129),
(7, 130), (17, 130), (27, 130), (37, 130), (47, 130), (49, 130),
(2, 131), (12, 131), (22, 131), (32, 131), (42, 131), (50, 131),
(4, 132), (14, 132), (24, 132), (34, 132), (44, 132), (51, 132),
(6, 133), (16, 133), (26, 133), (36, 133), (46, 133), (52, 133),
(8, 134), (18, 134), (28, 134), (38, 134), (48, 134), (53, 134),
(1, 135), (11, 135), (21, 135), (31, 135), (41, 135), (49, 135),
(3, 136), (13, 136), (23, 136), (33, 136), (43, 136), (50, 136),
(5, 137), (15, 137), (25, 137), (35, 137), (45, 137), (51, 137),
(7, 138), (17, 138), (27, 138), (37, 138), (47, 138), (52, 138),
(2, 139), (12, 139), (22, 139), (32, 139), (42, 139), (53, 139),
(4, 140), (14, 140), (24, 140), (34, 140), (44, 140), (49, 140),
-- Events 141-160
(6, 141), (16, 141), (26, 141), (36, 141), (46, 141), (50, 141),
(8, 142), (18, 142), (28, 142), (38, 142), (48, 142), (51, 142),
(1, 143), (11, 143), (21, 143), (31, 143), (41, 143), (52, 143),
(3, 144), (13, 144), (23, 144), (33, 144), (43, 144), (53, 144),
(5, 145), (15, 145), (25, 145), (35, 145), (45, 145), (49, 145),
(7, 146), (17, 146), (27, 146), (37, 146), (47, 146), (50, 146),
(2, 147), (12, 147), (22, 147), (32, 147), (42, 147), (51, 147),
(4, 148), (14, 148), (24, 148), (34, 148), (44, 148), (52, 148),
(6, 149), (16, 149), (26, 149), (36, 149), (46, 149), (53, 149),
(8, 150), (18, 150), (28, 150), (38, 150), (48, 150), (49, 150),
(1, 151), (11, 151), (21, 151), (31, 151), (41, 151), (50, 151),
(3, 152), (13, 152), (23, 152), (33, 152), (43, 152), (51, 152),
(5, 153), (15, 153), (25, 153), (35, 153), (45, 153), (52, 153),
(7, 154), (17, 154), (27, 154), (37, 154), (47, 154), (53, 154),
(2, 155), (12, 155), (22, 155), (32, 155), (42, 155), (49, 155),
(4, 156), (14, 156), (24, 156), (34, 156), (44, 156), (50, 156),
(6, 157), (16, 157), (26, 157), (36, 157), (46, 157), (51, 157),
(8, 158), (18, 158), (28, 158), (38, 158), (48, 158), (52, 158),
(1, 159), (11, 159), (21, 159), (31, 159), (41, 159), (53, 159),
(3, 160), (13, 160), (23, 160), (33, 160), (43, 160), (49, 160),
-- Events 161-168
(5, 161), (15, 161), (25, 161), (35, 161), (45, 161), (50, 161),
(7, 162), (17, 162), (27, 162), (37, 162), (47, 162), (51, 162),
(2, 163), (12, 163), (22, 163), (32, 163), (42, 163), (52, 163),
(4, 164), (14, 164), (24, 164), (34, 164), (44, 164), (53, 164),
(6, 165), (16, 165), (26, 165), (36, 165), (46, 165), (49, 165),
(8, 166), (18, 166), (28, 166), (38, 166), (48, 166), (50, 166),
(1, 167), (11, 167), (21, 167), (31, 167), (41, 167), (51, 167),
(3, 168), (13, 168), (23, 168), (33, 168), (43, 168), (52, 168);

INSERT INTO question_review (id, question_text) VALUES
(1, 'Was the event well organized?'),
(2, 'Did the event structure meet the needs?'),
(3, 'Were the volunteers well received and guided?'),
(4, 'Did the event achieve its proposed objective?'),
(5, 'Would you recommend this event to others?');

INSERT INTO answer_review (answered_by_id, question_id, about_event_id, rating, date) VALUES
(2, 1, 1, 4, '2023-11-16'),
(2, 2, 1, 5, '2023-11-16'),
(2, 3, 1, 4, '2023-11-16'),
(2, 4, 1, 5, '2023-11-16'),
(2, 5, 1, 4, '2023-11-16'),
(3, 1, 1, 5, '2023-11-16'),
(3, 2, 1, 5, '2023-11-16'),
(3, 3, 1, 5, '2023-11-16'),
(3, 4, 1, 3, '2023-11-16'),
(3, 5, 1, 3, '2023-11-16'),
(1, 1, 3, 3, '2023-12-02'),
(1, 2, 3, 3, '2023-12-02'),
(1, 3, 3, 3, '2023-12-02'),
(1, 4, 3, 3, '2023-12-02'),
(1, 5, 3, 3, '2023-12-02');

INSERT INTO comment (comment_by_id, event_id, text, is_pinned, score, edited, date) VALUES
-- Event 1 (8 comments)
(1, 1, 'This event looks amazing!', FALSE, 45, FALSE, '2023-11-15 09:00:00'),
(12, 1, 'Count me in! The agenda looks fantastic', FALSE, 32, FALSE, '2023-11-15 09:45:00'),
(23, 1, 'Will there be parking available?', FALSE, 8, FALSE, '2023-11-15 10:30:00'),
(5, 1, 'Last year''s event was great, expecting even better this time!', TRUE, 67, FALSE, '2023-11-15 11:15:00'),
(35, 1, 'Anyone want to carpool from downtown?', FALSE, 15, FALSE, '2023-11-15 13:20:00'),
(42, 1, 'Just registered! Looking forward to meeting everyone', FALSE, 28, FALSE, '2023-11-15 14:45:00'),
(15, 1, 'What''s the dress code?', FALSE, 12, TRUE, '2023-11-15 15:30:00'),
(8, 1, 'Is there still space available?', FALSE, 5, FALSE, '2023-11-15 16:15:00'),

-- Event 2 (5 comments)
(7, 2, 'Perfect timing for this type of event!', TRUE, 55, FALSE, '2023-11-16 08:30:00'),
(31, 2, 'Will presentations be recorded?', FALSE, 18, FALSE, '2023-11-16 09:45:00'),
(44, 2, 'Looking forward to the keynote speaker', FALSE, 42, FALSE, '2023-11-16 10:30:00'),
(3, 2, 'Is there a virtual attendance option?', FALSE, 25, TRUE, '2023-11-16 11:15:00'),
(50, 2, 'Great initiative!', FALSE, 38, FALSE, '2023-11-16 12:00:00'),

-- Event 3 (3 comments)
(17, 3, 'Can''t wait for this!', FALSE, 28, FALSE, '2023-11-17 09:00:00'),
(28, 3, 'Will refreshments be provided?', FALSE, 15, FALSE, '2023-11-17 10:30:00'),
(41, 3, 'The venue choice is perfect', TRUE, 48, FALSE, '2023-11-17 11:45:00'),

-- Event 4 (7 comments)
(4, 4, 'This is exactly what our community needed', TRUE, 72, FALSE, '2023-11-18 08:15:00'),
(19, 4, 'How long will the event last?', FALSE, 22, FALSE, '2023-11-18 09:30:00'),
(36, 4, 'Already blocked my calendar!', FALSE, 35, FALSE, '2023-11-18 10:45:00'),
(45, 4, 'Is there an agenda available?', FALSE, 28, TRUE, '2023-11-18 11:30:00'),
(11, 4, 'Looking forward to networking', FALSE, 42, FALSE, '2023-11-18 12:45:00'),
(27, 4, 'Will there be breakout sessions?', FALSE, 18, FALSE, '2023-11-18 13:15:00'),
(51, 4, 'Great speaker lineup!', FALSE, 58, FALSE, '2023-11-18 14:30:00'),

-- Event 5 (4 comments)
(13, 5, 'Excited to participate!', FALSE, 32, FALSE, '2023-11-19 09:00:00'),
(29, 5, 'Is this suitable for beginners?', FALSE, 25, FALSE, '2023-11-19 10:15:00'),
(46, 5, 'Looking forward to learning new things', TRUE, 45, FALSE, '2023-11-19 11:30:00'),
(6, 5, 'Will slides be shared afterward?', FALSE, 20, FALSE, '2023-11-19 12:45:00'),

-- Continue pattern for remaining events...
-- Event 6 (6 comments)
(24, 6, 'This topic is so relevant right now', TRUE, 65, FALSE, '2023-11-20 08:30:00'),
(39, 6, 'Can''t wait to contribute to the discussion', FALSE, 38, FALSE, '2023-11-20 09:45:00'),
(47, 6, 'Will there be Q&A sessions?', FALSE, 22, FALSE, '2023-11-20 10:30:00'),
(9, 6, 'Excellent initiative!', FALSE, 48, FALSE, '2023-11-20 11:15:00'),
(32, 6, 'Is there a hashtag for social media?', FALSE, 15, TRUE, '2023-11-20 12:30:00'),
(52, 6, 'Looking forward to meeting everyone', FALSE, 28, FALSE, '2023-11-20 13:45:00'),

-- Event 7 (2 comments)
(10, 7, 'Just what I was looking for!', TRUE, 42, FALSE, '2023-11-21 09:00:00'),
(38, 7, 'Will this be recorded?', FALSE, 18, FALSE, '2023-11-21 10:15:00'),

-- Event 8 (5 comments)
(14, 8, 'Great timing for this event', FALSE, 35, FALSE, '2023-11-22 08:45:00'),
(30, 8, 'Looking forward to the workshops', TRUE, 52, FALSE, '2023-11-22 09:30:00'),
(40, 8, 'Is there a detailed schedule?', FALSE, 25, FALSE, '2023-11-22 10:45:00'),
(48, 8, 'Will certificates be provided?', FALSE, 28, FALSE, '2023-11-22 11:30:00'),
(5, 8, 'Can''t wait to participate!', FALSE, 42, FALSE, '2023-11-22 12:15:00'),

-- Event 9 (9 comments)
(12, 9, 'This looks promising!', TRUE, 58, FALSE, '2023-11-23 08:00:00'),
(34, 9, 'Are materials provided?', FALSE, 22, FALSE, '2023-11-23 09:15:00'),
(50, 9, 'Perfect for team building', FALSE, 45, FALSE, '2023-11-23 10:30:00'),
(3, 9, 'Will there be networking breaks?', FALSE, 32, FALSE, '2023-11-23 11:45:00'),
(23, 9, 'Already registered my team', FALSE, 38, FALSE, '2023-11-23 12:30:00'),
(42, 9, 'Is there a dress code?', FALSE, 15, TRUE, '2023-11-23 13:15:00'),
(53, 9, 'Looking forward to learning', FALSE, 28, FALSE, '2023-11-23 14:00:00'),
(15, 9, 'Will there be group activities?', FALSE, 20, FALSE, '2023-11-23 14:45:00'),
(7, 9, 'Can''t wait!', FALSE, 25, FALSE, '2023-11-23 15:30:00'),

-- Event 10 (3 comments)
(25, 10, 'Perfect opportunity!', TRUE, 48, FALSE, '2023-11-24 09:00:00'),
(44, 10, 'Is there a virtual option?', FALSE, 22, FALSE, '2023-11-24 10:15:00'),
(35, 10, 'Looking forward to it', FALSE, 35, FALSE, '2023-11-24 11:30:00'),

-- Events 11-15 
(18, 11, 'This is exactly what I needed!', TRUE, 62, FALSE, '2023-11-25 08:30:00'),
(33, 11, 'Will there be hands-on sessions?', FALSE, 28, FALSE, '2023-11-25 09:45:00'),
(41, 11, 'Great initiative!', FALSE, 45, FALSE, '2023-11-25 10:30:00'),
(49, 11, 'Looking forward to networking', FALSE, 32, FALSE, '2023-11-25 11:15:00'),

(2, 12, 'Perfect timing', TRUE, 52, FALSE, '2023-11-26 09:00:00'),
(24, 12, 'Will there be follow-up sessions?', FALSE, 25, FALSE, '2023-11-26 10:15:00'),
(45, 12, 'Can''t wait to learn more', FALSE, 38, FALSE, '2023-11-26 11:30:00'),
(52, 12, 'Is there a preparation guide?', FALSE, 18, TRUE, '2023-11-26 12:45:00'),
(13, 12, 'This looks interesting', FALSE, 28, FALSE, '2023-11-26 13:30:00'),
(36, 12, 'Already registered!', FALSE, 42, FALSE, '2023-11-26 14:15:00'),

(16, 13, 'Great opportunity!', TRUE, 55, FALSE, '2023-11-27 08:45:00'),
(37, 13, 'Will there be group discussions?', FALSE, 28, FALSE, '2023-11-27 09:30:00'),
(46, 13, 'Looking forward to it', FALSE, 35, FALSE, '2023-11-27 10:45:00'),

(8, 14, 'This is exactly what we need', TRUE, 68, FALSE, '2023-11-28 09:00:00'),
(28, 14, 'Is there a maximum capacity?', FALSE, 22, FALSE, '2023-11-28 10:15:00'),
(47, 14, 'Will certificates be provided?', FALSE, 32, FALSE, '2023-11-28 11:30:00'),
(17, 14, 'Looking forward to participating', FALSE, 45, FALSE, '2023-11-28 12:45:00'),
(38, 14, 'Great initiative!', FALSE, 28, FALSE, '2023-11-28 13:30:00'),

(1, 15, 'Can''t wait!', TRUE, 58, FALSE, '2023-11-29 08:30:00'),
(27, 15, 'Will there be breaks?', FALSE, 25, FALSE, '2023-11-29 09:45:00'),
(48, 15, 'Perfect timing', FALSE, 42, FALSE, '2023-11-29 10:30:00'),
(19, 15, 'Looking forward to it', FALSE, 35, FALSE, '2023-11-29 11:15:00'),
(39, 15, 'Is there a detailed agenda?', FALSE, 28, TRUE, '2023-11-29 12:00:00'),
(53, 15, 'Just registered!', FALSE, 32, FALSE, '2023-11-29 12:45:00');

INSERT INTO comment (reply_to, comment_by_id, event_id, text, is_pinned, score, edited, date) VALUES
(3, 22, 1, 'Yes!', FALSE, 8, FALSE, '2023-11-15 10:32:00');

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
(1, 'comment_pic_2.JPG');