DROP SCHEMA IF EXISTS lbaw2414 CASCADE;

CREATE SCHEMA lbaw2414;

SET search_path TO lbaw2414;

-- drop tables
DROP TABLE IF EXISTS user_account CASCADE;
DROP TABLE IF EXISTS district CASCADE;
DROP TABLE IF EXISTS category CASCADE;
DROP TABLE IF EXISTS event CASCADE;
DROP TABLE IF EXISTS participator CASCADE;
DROP TABLE IF EXISTS question_review CASCADE;
DROP TABLE IF EXISTS comment CASCADE;
DROP TABLE IF EXISTS poll CASCADE;
DROP TABLE IF EXISTS poll_response CASCADE;
DROP TABLE IF EXISTS notification CASCADE;
DROP TABLE IF EXISTS admin CASCADE;
DROP TABLE IF EXISTS unblock_request CASCADE;
DROP TABLE IF EXISTS invite CASCADE;
DROP TABLE IF EXISTS invite_notification CASCADE;
DROP TABLE IF EXISTS accepted_invite_notification CASCADE;
DROP TABLE IF EXISTS join_request CASCADE;
DROP TABLE IF EXISTS join_request_notification CASCADE;
DROP TABLE IF EXISTS accepted_join_request_notification CASCADE;
DROP TABLE IF EXISTS event_update_notification CASCADE;
DROP TABLE IF EXISTS comment_notification CASCADE;
DROP TABLE IF EXISTS poll_reminder CASCADE;
DROP TABLE IF EXISTS event_cancelation CASCADE;
DROP TABLE IF EXISTS event_reminder CASCADE;
DROP TABLE IF EXISTS admin_action CASCADE;
DROP TABLE IF EXISTS unblock_notification CASCADE;
DROP TABLE IF EXISTS file CASCADE;
DROP TABLE IF EXISTS comment_feedback CASCADE;

-- drop indexes
DROP INDEX IF EXISTS idx_user_username;
DROP INDEX IF EXISTS idx_event_start_date;
DROP INDEX IF EXISTS idx_event_category;
DROP INDEX IF EXISTS idx_event_full_text_search;

-- drop triggers
DROP TRIGGER IF EXISTS event_search_update ON event;
DROP TRIGGER IF EXISTS trigger_create_invite_notification ON invite;
DROP TRIGGER IF EXISTS trigger_accepted_invite_notification ON invite;
DROP TRIGGER IF EXISTS trigger_create_join_request_notification ON join_request;
DROP TRIGGER IF EXISTS trigger_accepted_join_request_notification ON join_request;
DROP TRIGGER IF EXISTS trigger_comment_notification ON comment;
DROP TRIGGER IF EXISTS trigger_event_cancellation ON event;
DROP TRIGGER IF EXISTS trigger_event_update_notification ON event;
DROP TRIGGER IF EXISTS trigger_poll_reminder ON poll;
DROP TRIGGER IF EXISTS trigger_event_reminder_notification ON event;
DROP TRIGGER IF EXISTS trigger_update_event_reminders_start_date ON event;
DROP TRIGGER IF EXISTS trigger_update_event_reminders_end_date ON event;
DROP TRIGGER IF EXISTS trigger_unblock_notification ON unblock_request;
DROP TRIGGER IF EXISTS trigger_delete_future_notifications_user_left ON participant;
DROP TRIGGER IF EXISTS trigger_delete_future_notifications_event_updated ON event;
DROP TRIGGER IF EXISTS check_orphaned_notifications_comment ON comment_notification;
DROP TRIGGER IF EXISTS check_orphaned_notifications_invite ON invite_notification;
DROP TRIGGER IF EXISTS check_orphaned_notifications_accepted_invite ON accepted_invite_notification;
DROP TRIGGER IF EXISTS check_orphaned_notifications_join_request ON join_request_notification;
DROP TRIGGER IF EXISTS check_orphaned_notifications_accepted_join_request ON accepted_join_request_notification;
DROP TRIGGER IF EXISTS check_orphaned_notifications_poll_reminder ON poll_reminder;
DROP TRIGGER IF EXISTS check_orphaned_notifications_event_cancelation ON event_cancelation;
DROP TRIGGER IF EXISTS check_orphaned_notifications_event_reminder ON event_reminder;
DROP TRIGGER IF EXISTS check_orphaned_notifications_event_update ON event_update_notification;
DROP TRIGGER IF EXISTS check_orphaned_notifications_admin_action ON admin_action;
DROP TRIGGER IF EXISTS check_orphaned_notifications_unblock ON unblock_notification;

-- drop functions
DROP FUNCTION IF EXISTS event_search_update();
DROP FUNCTION IF EXISTS create_invite_notification();
DROP FUNCTION IF EXISTS create_accepted_invite_notification();
DROP FUNCTION IF EXISTS create_join_request_notification();
DROP FUNCTION IF EXISTS create_accepted_join_request_notification();
DROP FUNCTION IF EXISTS create_comment_notification();
DROP FUNCTION IF EXISTS notify_event_cancellation();
DROP FUNCTION IF EXISTS create_event_update_notification();
DROP FUNCTION IF EXISTS create_poll_reminder();
DROP FUNCTION IF EXISTS create_event_reminder_notification();
DROP FUNCTION IF EXISTS update_event_reminder_notifications_start_date();
DROP FUNCTION IF EXISTS update_event_reminder_notifications_end_date();
DROP FUNCTION IF EXISTS create_unblock_notification();
DROP FUNCTION IF EXISTS delete_future_notifications();
DROP FUNCTION IF EXISTS delete_future_notifications_on_canceled();
DROP FUNCTION IF EXISTS delete_orphaned_notifications();

-- create tables
CREATE TABLE user_account (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    username VARCHAR(255) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    isDeleted BOOLEAN DEFAULT FALSE,
    isBlocked BOOLEAN DEFAULT FALSE,
    file_id INT,
    remember_token VARCHAR(255) DEFAULT NULL,
    google_id VARCHAR
);

CREATE TABLE district (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE category (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) UNIQUE NOT NULL
);

CREATE TYPE VisibilityTypes AS ENUM ('public', 'private', 'request_only', 'canceled');

CREATE TABLE "event" (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    location TEXT,
    start_date TIMESTAMP NOT NULL,
    end_date TIMESTAMP NOT NULL,
    visibility VisibilityTypes NOT NULL,
    capacity INT,
    creator_id INT REFERENCES user_account(id) NOT NULL,
    district_id INT REFERENCES district(id) NOT NULL,
    category_id INT REFERENCES category(id) NOT NULL,
    file_id INT,
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
    date TIMESTAMP NOT NULL
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
    status VARCHAR(50) NOT NULL CHECK (status IN ('pending', 'approved', 'rejected')),
    requested_at TIMESTAMP NOT NULL CHECK (requested_at <= CURRENT_TIMESTAMP),
    processed_at TIMESTAMP DEFAULT NULL CHECK (processed_at >= requested_at),
    decision VARCHAR(255) NOT NULL
);

CREATE TABLE invite (
    id SERIAL PRIMARY KEY,
    sender_id INT REFERENCES user_account(id),
    receiver_id INT REFERENCES user_account(id),
    event_id INT REFERENCES event(id),
	accepted BOOLEAN,
    description VARCHAR(50) NOT NULL,
    date TIMESTAMP NOT NULL CHECK (date <= CURRENT_TIMESTAMP)
);

CREATE TABLE invite_notification (
    id INT REFERENCES notification(id) ON DELETE CASCADE,
    invite_id INT REFERENCES invite(id) ON DELETE CASCADE,
    PRIMARY KEY (id, invite_id)
);

CREATE TABLE accepted_invite_notification (
    id INT REFERENCES notification(id) ON DELETE CASCADE,
    invite_id INT REFERENCES invite(id) ON DELETE CASCADE,
    PRIMARY KEY (id, invite_id)
);

CREATE TABLE join_request (
    id SERIAL PRIMARY KEY,
    join_request_by_id INT REFERENCES user_account(id),
    event_id INT REFERENCES event(id) ON DELETE CASCADE,
	accepted BOOLEAN,
    date TIMESTAMP NOT NULL CHECK (date <= CURRENT_TIMESTAMP)
);

CREATE TABLE join_request_notification (
    id INT REFERENCES notification(id) ON DELETE CASCADE,
    join_request_id INT REFERENCES join_request(id) ON DELETE CASCADE,
    PRIMARY KEY (id, join_request_id)
);

CREATE TABLE accepted_join_request_notification (
    id INT REFERENCES notification(id) ON DELETE CASCADE,
    join_request_id INT REFERENCES join_request(id) ON DELETE CASCADE,
    PRIMARY KEY (id, join_request_id)
);

CREATE TABLE event_update_notification (
    id INT REFERENCES notification(id) ON DELETE CASCADE,
    event_id INT REFERENCES event(id)  ON DELETE CASCADE,
    PRIMARY KEY (id, event_id)
);

CREATE TABLE comment_notification (
    id INT REFERENCES notification(id) ON DELETE CASCADE,
    comment_id INT REFERENCES comment(id) ON DELETE CASCADE,
    PRIMARY KEY (id, comment_id)
);

CREATE TABLE poll_reminder (
    id INT REFERENCES notification(id) ON DELETE CASCADE,
    poll_id INT REFERENCES poll(id),
    PRIMARY KEY (id, poll_id)
);

CREATE TABLE event_cancelation (
    id INT REFERENCES notification(id) ON DELETE CASCADE,
    event_id INT REFERENCES event(id) ON DELETE CASCADE,
    PRIMARY KEY (id, event_id)
);

CREATE TYPE EventReminderType AS ENUM ('3 days', '1 day', 'survey');

CREATE TABLE event_reminder (
    id INT REFERENCES notification(id) ON DELETE CASCADE,
    event_id INT REFERENCES event(id) ON DELETE CASCADE,
    about EventReminderType NOT NULL,
    PRIMARY KEY (id, event_id)
);

CREATE TABLE admin_action (
    id INT REFERENCES notification(id) ON DELETE CASCADE,
    admin_id INT REFERENCES admin(id),
    action VARCHAR(255) NOT NULL,
    PRIMARY KEY (id, admin_id)
);

CREATE TABLE unblock_notification (
    id INT REFERENCES notification(id) ON DELETE CASCADE,
    admin_id INT REFERENCES admin(id),
    PRIMARY KEY (id, admin_id)
);

CREATE TABLE file (
    id SERIAL PRIMARY KEY,
    comment_id INT,
    path VARCHAR(255) NOT NULL
);

CREATE TABLE comment_feedback (
    user_id INT REFERENCES user_account(id),
    comment_id INT REFERENCES comment(id) ON DELETE CASCADE,
    feedback INT NOT NULL,
    PRIMARY KEY (user_id, comment_id)
);

ALTER TABLE "event"
	ADD CONSTRAINT "FK_event_file" FOREIGN KEY ("file_id") REFERENCES "file" ("id") ON UPDATE NO ACTION ON DELETE CASCADE;

ALTER TABLE "user_account"
	ADD CONSTRAINT "FK_user_account_file" FOREIGN KEY ("file_id") REFERENCES "file" ("id") ON UPDATE NO ACTION ON DELETE CASCADE;

ALTER TABLE "file"
	ADD CONSTRAINT "FK_file_comment" FOREIGN KEY ("comment_id") REFERENCES "comment" ("id") ON UPDATE NO ACTION ON DELETE NO ACTION;


-- Indexes

CREATE UNIQUE INDEX idx_user_username ON user_account(username);

CREATE INDEX idx_event_start_date ON event(start_date);

CREATE INDEX idx_event_category ON event(category_id); CLUSTER event USING idx_event_category;

-- Search

ALTER TABLE event ADD COLUMN ts_vector TSVECTOR;


UPDATE event
SET ts_vector = to_tsvector('english', name || ' ' || description || ' ' ||
                COALESCE((SELECT name FROM district WHERE district.id = event.district_id), '') || ' ' ||
                COALESCE((SELECT name FROM category WHERE category.id = event.category_id), '')
);

CREATE FUNCTION event_search_update() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.ts_vector := (
            setweight(to_tsvector('english', NEW.name), 'A') ||
            setweight(to_tsvector('english', NEW.description), 'B') ||
            setweight(to_tsvector('english', COALESCE((SELECT name FROM district WHERE district.id = NEW.district_id), '')), 'C') ||
            setweight(to_tsvector('english', COALESCE((SELECT name FROM category WHERE category.id = NEW.category_id), '')), 'C')
        );
    END IF;

    IF TG_OP = 'UPDATE' THEN
        IF (NEW.name <> OLD.name OR 
            NEW.description <> OLD.description OR 
            NEW.district_id <> OLD.district_id OR 
            NEW.category_id <> OLD.category_id) THEN

            NEW.ts_vector := (
                setweight(to_tsvector('english', NEW.name), 'A') ||
                setweight(to_tsvector('english', NEW.description), 'B') ||
                setweight(to_tsvector('english', COALESCE((SELECT name FROM district WHERE district.id = NEW.district_id), '')), 'C') ||
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

-- create invite notification
CREATE OR REPLACE FUNCTION create_invite_notification() RETURNS TRIGGER AS
$BODY$
DECLARE
    notification_id INT;
BEGIN
    INSERT INTO notification (emitted_by, emitted_to, date)
    VALUES (NEW.sender_id, NEW.receiver_id, CURRENT_TIMESTAMP)
    RETURNING id INTO notification_id;

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

-- create accepted invite notification
CREATE FUNCTION create_accepted_invite_notification() RETURNS TRIGGER AS
$BODY$
DECLARE
    notification_id INTEGER; 
BEGIN
    IF NEW.accepted = TRUE THEN
        INSERT INTO notification (emitted_by, emitted_to, date)
        VALUES (NEW.receiver_id, NEW.sender_id, CURRENT_TIMESTAMP)
        RETURNING id INTO notification_id;

        INSERT INTO accepted_invite_notification (id, invite_id)
        VALUES (notification_id, NEW.id);
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


-- create join request notification
CREATE OR REPLACE FUNCTION create_join_request_notification() RETURNS TRIGGER AS
$BODY$
DECLARE
    notification_id INT;
    join_request_to_id INT;
BEGIN
    SELECT creator_id INTO join_request_to_id
    FROM event
    WHERE id = NEW.event_id;

    INSERT INTO notification (emitted_by, emitted_to, date)
    VALUES (NEW.join_request_by_id, join_request_to_id, CURRENT_TIMESTAMP)
    RETURNING id INTO notification_id;

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

-- create accepted join request notification
CREATE OR REPLACE FUNCTION create_accepted_join_request_notification() RETURNS TRIGGER AS
$BODY$
DECLARE
    notification_id INT;
    join_request_to_id INT;
BEGIN
    SELECT creator_id INTO join_request_to_id
    FROM event
    WHERE id = NEW.event_id;

    IF NEW.accepted = TRUE THEN
        INSERT INTO notification (emitted_by, emitted_to, date)
        VALUES (join_request_to_id, NEW.join_request_by_id, CURRENT_TIMESTAMP)
        RETURNING id INTO notification_id;

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


-- create comment notification
CREATE FUNCTION create_comment_notification() RETURNS TRIGGER AS
$BODY$
DECLARE
    notification_id INT;
    participant RECORD;
BEGIN
    FOR participant IN
        SELECT participator_id 
        FROM participator 
        WHERE event_id = (SELECT event_id FROM comment WHERE id = NEW.id)
    LOOP
        IF NEW.comment_by_id != participant.participator_id THEN
            INSERT INTO notification (emitted_by, emitted_to, date)
            VALUES (NEW.comment_by_id, participant.participator_id, CURRENT_TIMESTAMP)
            RETURNING id INTO notification_id;

            INSERT INTO comment_notification (id, comment_id)
            VALUES (notification_id, NEW.id);
        END IF;
    END LOOP;

    RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER trigger_comment_notification
    AFTER INSERT ON comment
    FOR EACH ROW
    EXECUTE FUNCTION create_comment_notification();


-- create event cancellation notification
CREATE OR REPLACE FUNCTION notify_event_cancellation() RETURNS TRIGGER AS
$BODY$
DECLARE
    notification_id INT;
    participant RECORD;
BEGIN
    FOR participant IN
        SELECT participator_id FROM participator WHERE event_id = NEW.id
    LOOP
        INSERT INTO notification (emitted_by, emitted_to, date)
        VALUES (NEW.creator_id, participant.participator_id, CURRENT_TIMESTAMP)
        RETURNING id INTO notification_id;

        INSERT INTO event_cancelation (id, event_id)
        VALUES (notification_id, NEW.id);
    END LOOP;

    RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER trigger_event_cancellation
    AFTER UPDATE OF visibility ON event
    FOR EACH ROW
    WHEN (OLD.visibility IS DISTINCT FROM 'canceled' AND NEW.visibility = 'canceled')
    EXECUTE FUNCTION notify_event_cancellation();


-- create event update notification
CREATE FUNCTION create_event_update_notification() RETURNS TRIGGER AS
$BODY$
DECLARE
    notification_id INT;
    participant RECORD;
BEGIN
    FOR participant IN
        SELECT participator_id FROM participator WHERE event_id = NEW.id
    LOOP
        INSERT INTO notification (emitted_by, emitted_to, date)
        VALUES (NEW.creator_id, participant.participator_id, CURRENT_TIMESTAMP)
        RETURNING id INTO notification_id;

        INSERT INTO event_update_notification (id, event_id)
        VALUES (notification_id, NEW.id);
    END LOOP;

    RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER trigger_event_update_notification
    AFTER UPDATE OF name, description, start_date, end_date, district_id, category_id ON event
    FOR EACH ROW
    EXECUTE FUNCTION create_event_update_notification();


-- create poll reminder notification
CREATE FUNCTION create_poll_reminder() RETURNS TRIGGER AS
$BODY$
DECLARE
    notification_id INT;
    participant RECORD;
    event_record RECORD;
BEGIN
    SELECT * INTO event_record
    FROM event
    WHERE id = NEW.event_id;

    IF event_record IS NULL THEN
        RAISE EXCEPTION 'Event not found for event_id: %', NEW.event_id;
    END IF;

    FOR participant IN
        SELECT participator_id
        FROM participator
        WHERE event_id = NEW.event_id
    LOOP
        INSERT INTO notification (emitted_by, emitted_to, date)
        VALUES (event_record.creator_id, participant.participator_id, CURRENT_TIMESTAMP)
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


-- create event reminder notification
CREATE OR REPLACE FUNCTION create_event_reminder_notification()
RETURNS TRIGGER AS
$BODY$
DECLARE
    notification_id INT;
    reminder_date TIMESTAMP;
    event_start_date TIMESTAMP;
    event_creator_id INT;
BEGIN
    SELECT start_date, creator_id INTO event_start_date, event_creator_id
    FROM event
    WHERE event.id = NEW.event_id;

    IF event_start_date IS NULL OR event_creator_id IS NULL THEN
        RAISE EXCEPTION 'Event data (start_date or creator_id) not found for event ID %', NEW.event_id;
    END IF;

    -- 3 days before the event
    IF event_start_date - INTERVAL '3 days' >= CURRENT_TIMESTAMP THEN
        reminder_date := event_start_date - INTERVAL '3 days';

        INSERT INTO notification (emitted_by, emitted_to, date)
        VALUES (event_creator_id, NEW.participator_id, reminder_date)
        RETURNING id INTO notification_id;

        INSERT INTO event_reminder (id, event_id, about)
        VALUES (notification_id, NEW.event_id, '3 days');
    END IF;

    -- 1 day before the event
    IF event_start_date - INTERVAL '1 day' >= CURRENT_TIMESTAMP THEN
        reminder_date := event_start_date - INTERVAL '1 day';

        INSERT INTO notification (emitted_by, emitted_to, date)
        VALUES (event_creator_id, NEW.participator_id, reminder_date)
        RETURNING id INTO notification_id;

        INSERT INTO event_reminder (id, event_id, about)
        VALUES (notification_id, NEW.event_id, '1 day');
    END IF;

    -- survey reminder after the event ends
    reminder_date := event_start_date + INTERVAL '1 day';

    INSERT INTO notification (emitted_by, emitted_to, date)
    VALUES (event_creator_id, NEW.participator_id, reminder_date)
    RETURNING id INTO notification_id;

    INSERT INTO event_reminder (id, event_id, about)
    VALUES (notification_id, NEW.event_id, 'survey');

    RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER trigger_event_reminder_notification
AFTER INSERT ON participator
FOR EACH ROW
EXECUTE FUNCTION create_event_reminder_notification();


-- update event reminder notification after change on start_date
CREATE OR REPLACE FUNCTION update_event_reminder_notifications_start_date()
RETURNS TRIGGER AS
$BODY$
BEGIN
    -- check if the start date of the event has changed
    IF NEW.start_date <> OLD.start_date THEN
        -- '3 days' reminders
        UPDATE notification
        SET date = NEW.start_date - INTERVAL '3 days'
        WHERE id IN (
            SELECT id
            FROM event_reminder
            WHERE event_id = NEW.id AND about = '3 days'
        );

        -- '1 day' reminders
        UPDATE notification
        SET date = NEW.start_date - INTERVAL '1 day'
        WHERE id IN (
            SELECT id
            FROM event_reminder
            WHERE event_id = NEW.id AND about = '1 day'
        );
    END IF;

    RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER trigger_update_event_reminders_start_date
BEFORE UPDATE ON event
FOR EACH ROW
WHEN (OLD.start_date IS DISTINCT FROM NEW.start_date)
EXECUTE FUNCTION update_event_reminder_notifications_start_date();


-- update event reminder notification after change on end_date
CREATE OR REPLACE FUNCTION update_event_reminder_notifications_end_date()
RETURNS TRIGGER AS
$BODY$
BEGIN
    -- check if the end date of the event has changed
    IF NEW.end_date <> OLD.end_date THEN
        -- surveys reminders
        UPDATE notification
        SET date = NEW.end_date + INTERVAL '1 hour'
        WHERE id IN (
            SELECT id
            FROM event_reminder
            WHERE event_id = NEW.id AND about = 'survey'
        );
    END IF;

    RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER trigger_update_event_reminders_end_date
BEFORE UPDATE ON event
FOR EACH ROW
WHEN (OLD.end_date IS DISTINCT FROM NEW.end_date)
EXECUTE FUNCTION update_event_reminder_notifications_end_date();


-- create unblock notification
CREATE FUNCTION create_unblock_notification() RETURNS TRIGGER AS
$BODY$
DECLARE
    notification_id INT;
BEGIN
    IF (NEW.status IN ('approved', 'denied')) AND (OLD.status IS DISTINCT FROM NEW.status) THEN
        INSERT INTO notification (emitted_by, emitted_to, date)
        VALUES (NEW.decision_by_id, NEW.requested_by_id, CURRENT_TIMESTAMP)
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

-- delete reminders notifications when user is removed from event
CREATE OR REPLACE FUNCTION delete_future_notifications()
RETURNS TRIGGER AS $$
BEGIN
    -- delete future notifications associated with the participator and the event
    DELETE FROM notification
    WHERE id IN (
        SELECT er.id
        FROM event_reminder er
        WHERE er.event_id = OLD.event_id
          AND EXISTS (
              SELECT 1
              FROM notification n
              WHERE n.id = er.id
                AND n.emitted_to = OLD.participator_id
                AND n.date > NOW()
          )
    );
    RETURN OLD;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_delete_future_notifications_user_left
BEFORE DELETE ON participator
FOR EACH ROW
EXECUTE FUNCTION delete_future_notifications();


-- delete future notifications when event is canceled
CREATE OR REPLACE FUNCTION delete_future_notifications_on_canceled() 
RETURNS TRIGGER AS $BODY$
BEGIN
    -- delete invites
    DELETE FROM notification
    WHERE id IN (
        SELECT id 
        FROM invite_notification 
        WHERE invite_id IN (
            SELECT id FROM invite WHERE event_id = NEW.id
        )
    );

    -- delete join requests
    DELETE FROM notification
    WHERE id IN (
        SELECT id 
        FROM join_request_notification 
        WHERE join_request_id IN (
            SELECT id FROM join_request WHERE event_id = NEW.id
        )
    );

    -- delete futures reminders for the event
    DELETE FROM notification
    WHERE id IN (
        SELECT id 
        FROM event_reminder 
        WHERE event_id = NEW.id
    )
    AND date > CURRENT_TIMESTAMP;

    RETURN NEW;
END;
$BODY$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_delete_future_notifications_event_updated
AFTER UPDATE OF visibility ON event
FOR EACH ROW
WHEN (NEW.visibility = 'canceled' AND OLD.visibility <> 'canceled')
EXECUTE FUNCTION delete_future_notifications_on_canceled();

-- delete from notification table
CREATE OR REPLACE FUNCTION delete_orphaned_notifications() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM comment_notification WHERE id = OLD.id
        UNION ALL
        SELECT 1 FROM invite_notification WHERE id = OLD.id
        UNION ALL
        SELECT 1 FROM accepted_invite_notification WHERE id = OLD.id
        UNION ALL
        SELECT 1 FROM join_request_notification WHERE id = OLD.id
        UNION ALL
        SELECT 1 FROM accepted_join_request_notification WHERE id = OLD.id
        UNION ALL
        SELECT 1 FROM poll_reminder WHERE id = OLD.id
        UNION ALL
        SELECT 1 FROM event_cancelation WHERE id = OLD.id
        UNION ALL
        SELECT 1 FROM event_reminder WHERE id = OLD.id
        UNION ALL
        SELECT 1 FROM event_update_notification WHERE id = OLD.id
        UNION ALL
        SELECT 1 FROM admin_action WHERE id = OLD.id
        UNION ALL
        SELECT 1 FROM unblock_notification WHERE id = OLD.id
    ) THEN
        DELETE FROM notification WHERE id = OLD.id;
    END IF;
    
    RETURN OLD;
END;
$BODY$
LANGUAGE plpgsql;

-- Trigger for deleted comment_notification
CREATE TRIGGER check_orphaned_notifications_comment
AFTER DELETE ON comment_notification
FOR EACH ROW
EXECUTE FUNCTION delete_orphaned_notifications();

-- Trigger for deleted invite_notification
CREATE TRIGGER check_orphaned_notifications_invite
AFTER DELETE ON invite_notification
FOR EACH ROW
EXECUTE FUNCTION delete_orphaned_notifications();

-- Trigger for deleted accepted_invite_notification
CREATE TRIGGER check_orphaned_notifications_accepted_invite
AFTER DELETE ON accepted_invite_notification
FOR EACH ROW
EXECUTE FUNCTION delete_orphaned_notifications();

-- Trigger for deleted join_request_notification
CREATE TRIGGER check_orphaned_notifications_join_request
AFTER DELETE ON join_request_notification
FOR EACH ROW
EXECUTE FUNCTION delete_orphaned_notifications();

-- Trigger for deleted accepted_join_request_notification
CREATE TRIGGER check_orphaned_notifications_accepted_join_request
AFTER DELETE ON accepted_join_request_notification
FOR EACH ROW
EXECUTE FUNCTION delete_orphaned_notifications();

-- Trigger for deleted poll_reminder
CREATE TRIGGER check_orphaned_notifications_poll_reminder
AFTER DELETE ON poll_reminder
FOR EACH ROW
EXECUTE FUNCTION delete_orphaned_notifications();

-- Trigger for deleted event_cancelation
CREATE TRIGGER check_orphaned_notifications_event_cancelation
AFTER DELETE ON event_cancelation
FOR EACH ROW
EXECUTE FUNCTION delete_orphaned_notifications();

-- Trigger for deleted event_reminder
CREATE TRIGGER check_orphaned_notifications_event_reminder
AFTER DELETE ON event_reminder
FOR EACH ROW
EXECUTE FUNCTION delete_orphaned_notifications();

-- Trigger for deleted event_update_notification
CREATE TRIGGER check_orphaned_notifications_event_update
AFTER DELETE ON event_update_notification
FOR EACH ROW
EXECUTE FUNCTION delete_orphaned_notifications();

-- Trigger for deleted admin_action
CREATE TRIGGER check_orphaned_notifications_admin_action
AFTER DELETE ON admin_action
FOR EACH ROW
EXECUTE FUNCTION delete_orphaned_notifications();

-- Trigger for deleted unblock_notification
CREATE TRIGGER check_orphaned_notifications_unblock
AFTER DELETE ON unblock_notification
FOR EACH ROW
EXECUTE FUNCTION delete_orphaned_notifications();


-- INSERT ------------------------------------------------------------------------------------------------------------

INSERT INTO file (path) VALUES
('ana.png'),
('joao.png'),
('maria.png'),
('pedro.png'),
('0-mental-health-awareness-training-workshop-thumb.jpg'),
('6-benefits-of-summer-reading-programs.jpg'),
('urban-gardening-initiative-stockcake.jpg'),
('Crestwood-Manor-519545542.jpg'),
('1662414732399.jpg'),
('SASItVqIRhNxeRQ6DNEKuFtyyEzxvgu61619458342.jpg'),
('mandatory-training.png'),
('images.png'),
('website-banner-youth-digital-skills.jpg'),
('00409-MED.jpg'),
('images.jpg'),
('homepage.jpg'),
('0socialjustice.png'),
('2XU-Wellness-Run1917.jpg'),
('1-1.jpg'),
('Volunteer.jpg'),
('pasted image 0.png'),
('art-class-fun-stockcake.jpg'),
('04-non-profits-it-support.jpg'),
('Food-Bank-1.jpg'),
('Mental-Resilience-Training.jpg'),
('MS-Assisi-Early-Learning-Center-079.jpg'),
('CULTURAL_MUSIC_FEST_2023_3version.jpg'),
('637bcef380dfa8903c709927_AdobeStock_265651458.jpg'),
('WhatsApp Image 2024-07-08 at 11.26.34 AM (1).jpeg'),
('health-screening-for-community-health-fair.jpg'),
('Depositphotos_117210136_original-375x250.jpg'),
('science-behind-art-restoration-2-1.jpg'),
('pexels-julia-m-cameron-6994985.jpg'),
('ems.jpg'),
('child-care-connections-food-programs-needs.jpg'),
('logo_digital-rights.jpg'),
('IT-Support-For-Non-Profits-scaled-1.jpg'),
('yoga.jpg'),
('Private-15.jpg'),
('253131-2121x1414-senior-on-a-seminar.jpg'),
('disastertraining10.jpg'),
('community-meal-preparation-stockcake.jpg'),
('Samtale-Generationer-COLOURBOX46752233--scaled.jpg'),
('Private-10-1.jpg'),
('group-tutoring.jpg'),
('Mobile-Wellness-Van-e1636140315427.jpg'),
('DSC_6278-scaled.jpg'),
('Home-Care-Services-Care-and-Companionship-Mobile-48.jpeg'),
('HEADER_Healing_PTSD_Through_Art_Therapy.jpg'),
('community_legal_aid_services_logo.jpg'),
('1718094517455.png'),
('AI-in-Student-Life-The-Pros-and-Cons-21-1.jpg'),
('bioprocess-innovation-center-2400x1350_clark-nexsen.jpg'),
('Hospital-Buddy-Pic-360x360-1.jpg'),
('resilientneighborhoods.jpg'),
('AdobeStock_397755557-scaled.jpeg'),
('Screen-Shot-2021-05-21-at-12.19.55-PM.png'),
('image-EgEa0fD1Xq-kmg-large.width-1200.format-webp.jpg'),
('JTG_4267.jpg'),
('page-ert_img01.jpg'),
('email-blocks-6.jpg'),
('kiwanisbuild02_h.jpg'),
('Join-a-fun-beach-clean-up-in-the-run-up-to-the-Absa-RUN-YOUR-CITY-DURBAN-10K-scaled.jpg'),
('Interior-Painting-1.jpeg'),
('IMG_1291-_1_-1-1.jpg'),
('KnowESG_Reforestation.jpeg'),
('computerseniors.jpg'),
('GettyImages-157532338-7390247e2ec54346a8bf34e59db93972.jpg'),
('homeless-shelter-volunteer-2-1024x672.jpeg'),
('patient-damaya-art-therapy-00188999-007.jpg'),
('IFH_HealthScreengs.jpeg'),
('refugees-welcome-march-london-2016-1-1350x900-1350x900.jpg'),
('three-forks-natural-area-planting-volunteer-event.jpg'),
('Street-Art-Graffiti.jpg'),
('P1010042.jpg'),
('Rescue_G_872576234.jpg'),
('Bookshelf.png'),
('maxresdefault.jpg'),
('6c98c946-food-kitchens-uk_featured-image.png'),
('dreamstime_l_155745624.jpg'),
('environmental-education-summer-camps.jpg'),
('GettyImages-463286667.jpg'),
('Untitled.jpg'),
('iStock-678867146-400x267.jpg'),
('IMG20231110094015-scaled-e1703773593480.jpg'),
('05-11-Mental-HEalth-Walk.jpg'),
('EnablingAction.jpg'),
('0socialjusticee.png'),
('School-Sports-Day-680x426.jpg'),
('digital-nomads.jpg'),
('animal.jpg'),
('doctor-with-parent-and-child.jpg'),
('DSC_1801.jpg'),
('Cultural-Exchange-Programs-Enhancing-Global-Understanding-Collaboration.jpg'),
('Youth-Leadership-new-photo-scaled.jpg'),
('20190710_122204-1.jpg'),
('2015-06-22_EF-Group1_CMYK_0578_0.jpg'),
('What-is-Involved-in-Becoming-an-Emergency-Medical-Technician-EMT.jpg'),
('Digital-Accessibility-medium.png'),
('safety.jpg'),
('TopBanner2.jpg'),
('Health-Education-Seminars-and-Webinars-for-Wellness.jpg'),
('180130_MM_blood002.jpg'),
('Read-to-Succeed_RGB-200x149.png'),
('SchoolDrive.jpg'),
('iStock-172427597-index.jpg'),
('Women-Tutoring-two-Kids-smartlearningctrs-1.jpg'),
('IMG_0062-scaled-e1658227387836.jpg'),
('large_Docent_Gianie_Kim_leads_a_tour_in_the_Grant_Wood_show_2018.jpg'),
('painting-workshop.jpg'),
('cep-logo.png'),
('reasons-upgrade-dt-classrooms.png'),
('Coding-Projects-for-Kids-Make-Programming-Fun.jpg'),
('1672120589118.png'),
('64e35c0f3408ab47bd28254f_The-Kenwood-Senior-Living-01r.jpg'),
('Mental-Health.jpg'),
('Peer support changes lives_ (3)(2).png'),
('self-care.jpg'),
('child-counselling-e1607674508557.jpg'),
('Clean-Local-Parks.jpg'),
('Plant-a-Tree.jpg'),
('workshop-na-ulusofona-descomplica-a-reciclagem-e-promove-consciencia-ambiental_600.jpg'),
('honeypreet_insan_1200x768.jpg'),
('950ec0_ec531002b0c443d582c98176291e3878~mv2.jpg'),
('66f6d3943dd432232b056589_65816b55a045a6e103efb999_626156b31d84dd5cd7dfa308_Mentorship_2520_25202.png'),
('clothing-donations.jpg'),
('senior-assistance-program.jpg'),
('haklar-projesi.png.jpg'),
('58_legal-aid.jpg'),
('653b95fc28783203cf7de3b5_Screenshot-2023-10-27-at-11.49.52.png'),
('042_Voicesstand_hp.jpg'),
('Horizontal-photo-500-×-300-px-31.png'),
('1140x655-home-health-aide.jpg'),
('Memory-Care-Activities-for-Seniors-with-Dementia-1024x683.jpg'),
('bigstock-Help-And-Support-Asian-Senior-346130380.jpg'),
('SBM-Disaster-Preparedness-Workshop.JPG'),
('201808190242575876_Model-Citizen-Collecting-relief-items-for-floodaffected_SECVPF.jpg'),
('IMG_4461.jpg'),
('mass-care-distribution-e1627683833518.png'),
('how-to-adopt-a-stray-dog-an-easy-step-by-step-guide-552644.jpg'),
('hq720.jpg'),
('VIER PFOTEN_2020-03-30_077-1349x935.jpg'),
('eubeachcleanup_-_angola.jpg');

-- Users with profile pictures
INSERT INTO user_account (name, username, email, password, isDeleted, isBlocked, file_id) VALUES
('Ana Silva', 'anasilva', 'ana.silva@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 1),
('João Pereira', 'joaopereira', 'joao.pereira@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE, 2),
('Maria Costa', 'mariacosta', 'maria.costa@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, TRUE, 3),
('Pedro Sousa', 'pedrosousa', 'pedro.sousa@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', TRUE, FALSE, 4);

-- Users without profile pictures
INSERT INTO user_account (name, username, email, password, isDeleted, isBlocked) VALUES
('Carlos Santos', 'carlossantos', 'carlos.santos@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Sofia Oliveira', 'sofiaoliveira', 'sofia.oliveira@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Ricardo Lima', 'ricardolima', 'ricardo.lima@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Beatriz Martins', 'beatrizmartins', 'beatriz.martins@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Miguel Ferreira', 'miguelferreira', 'miguel.ferreira@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Catarina Alves', 'catarinaalves', 'catarina.alves@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Diogo Santos', 'diogosantos', 'diogo.santos@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Inês Rodrigues', 'inesrodrigues', 'ines.rodrigues@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Tiago Fernandes', 'tiagofernandes', 'tiago.fernandes@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Mariana Costa', 'marianacosta', 'mariana.costa@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('André Silva', 'andresilva', 'andre.silva@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Carolina Pereira', 'carolinapereira', 'carolina.pereira@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Francisco Martins', 'franciscomartins', 'francisco.martins@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Teresa Santos', 'teresasantos', 'teresa.santos@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Paulo Ferreira', 'pauloferreira', 'paulo.ferreira@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Marta Oliveira', 'martaoliveira', 'marta.oliveira@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('António Costa', 'antoniocosta', 'antonio.costa@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Raquel Lima', 'raquellima', 'raquel.lima@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Hugo Alves', 'hugoalves', 'hugo.alves@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Rita Pereira', 'ritapereira', 'rita.pereira@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Daniel Santos', 'danielsantos', 'daniel.santos@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Filipa Costa', 'filipacosta', 'filipa.costa@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Gonçalo Silva', 'goncalosilva', 'goncalo.silva@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Sara Martins', 'saramartins', 'sara.martins@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Rui Ferreira', 'ruiferreira', 'rui.ferreira@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Joana Santos', 'joanasantos', 'joana.santos@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Bruno Oliveira', 'brunooliveira', 'bruno.oliveira@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Clara Pereira', 'clarapereira', 'clara.pereira@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('David Lima', 'davidlima', 'david.lima@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Eva Costa', 'evacosta', 'eva.costa@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Fernando Silva', 'fernandosilva', 'fernando.silva@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Gabriela Santos', 'gabrielasantos', 'gabriela.santos@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Henrique Martins', 'henriquemartins', 'henrique.martins@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Isabel Ferreira', 'isabelferreira', 'isabel.ferreira@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Jorge Alves', 'jorgealves', 'jorge.alves@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Laura Oliveira', 'lauraoliveira', 'laura.oliveira@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Manuel Costa', 'manuelcosta', 'manuel.costa@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Nadia Pereira', 'nadiapereira', 'nadia.pereira@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Patricia Lima', 'patricialima', 'patricia.lima@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Quinten Silva', 'quintensilva', 'quinten.silva@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Rosa Martins', 'rosamartins', 'rosa.martins@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Sergio Ferreira', 'sergioferreira', 'sergio.ferreira@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Tania Oliveira', 'taniaoliveira', 'tania.oliveira@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Umberto Costa', 'umbertocosta', 'umberto.costa@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Vera Alves', 'veraalves', 'vera.alves@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Xavier Santos', 'xaviersantos', 'xavier.santos@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Yara Pereira', 'yarapereira', 'yara.pereira@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Zeca Lima', 'zecalima', 'zeca.lima@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE),
('Oscar Santos', 'oscarsantos', 'oscar.santos@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', FALSE, FALSE);

INSERT INTO admin (email, password) VALUES
('admin1@example.com', '$2y$10$ii5KNGv4MCXCh20tghqKg./7AzxujQjCGIvcRe5CO1iXYIUK59CoS'),
('admin2@example.com', '$2y$10$IEkOO9H2AQe1WSf6qYZ5MeWBLzcvcOzh3koKw93wfQiuJBOvN9LmG');

INSERT INTO district (name) VALUES
('Aveiro'),
('Beja'),
('Braga'),
('Bragança'),
('Castelo Branco'),
('Coimbra'),
('Évora'),
('Faro'),
('Guarda'),
('Leiria'),
('Lisboa'),
('Madeira'),
('Portalegre'),
('Porto'),
('Santarém'),
('Setúbal'),
('Viana do Castelo'),
('Vila Real'),
('Viseu'),
('Açores'),
('Multiple districts'),
('European'),
('Internacional');

INSERT INTO category (name) VALUES
('Health and Wellness'),
('Early Childhood Education'),
('Art and Culture'),
('Digital Inclusion'),
('Psychological Support'),
('Environmental Awareness'),
('Community Support'),
('Social Justice'),
('Senior Care'),
('Disaster Relief'),
('Animal Welfare and Conservation');

-- Past events
INSERT INTO event (name, description, location, start_date, end_date, visibility, capacity, creator_id, district_id, category_id, file_id) VALUES
('Mental Health Awareness Workshop', 'Interactive session on understanding mental health challenges. This workshop will explore depression, anxiety, PTSD, and mental wellness strategies. Participants will learn about identifying symptoms, providing support, and reducing stigma. The focus is on fostering empathy and awareness in the community.', 'Teatro Viriato', '2024-02-15 09:00', '2024-02-16 17:00', 'public', 50, 1, 19, 5, 5),
('Children''s Reading Program', 'Volunteer reading sessions aimed at underprivileged children to develop literacy skills. Volunteers will engage children in storytelling and interactive learning, promoting literacy and cognitive development in disadvantaged areas. The goal is to help foster a love for reading in young minds.', 'Biblioteca Almeida Garrett', '2024-03-10 10:00', '2024-05-20 16:00', 'public', 30, 2, 14, 2, 6),
('Urban Gardening Initiative', 'Community garden planting to foster sustainability and food security. Participants will learn about urban agriculture, soil health, and irrigation systems while contributing to a community garden. The event aims to build connections and promote eco-friendly practices.', 'Parque Natural de São Mamede', '2024-04-22 08:00', '2024-06-30 18:00', 'public', 40, 3, 13, 6, 7),
('Senior Technology Training', 'Training elderly individuals in digital skills like internet navigation, email, and online safety. The goal is to reduce digital isolation and increase confidence in technology use. Participants will gain skills to access essential services and connect with others online.', 'Biblioteca Municipal de Castelo Branco', '2024-01-15 10:00', '2024-03-15 15:00', 'public', 25, 4, 5, 4, 8),
('Art Therapy for Trauma Survivors', 'Creative workshop aimed at supporting psychological recovery. Art therapy helps trauma survivors express their emotions through creative mediums. This workshop encourages self-expression, emotional healing, and mindfulness practices to build resilience after trauma.', 'Museu Grão Vasco', '2024-02-01 09:30', '2024-02-28 16:00', 'request_only', 20, 5, 19, 5, 9),
('Community Clean-Up Day', 'City-wide environmental cleaning event focusing on waste collection, recycling, and beautifying public spaces. The aim is to improve community well-being and promote environmental stewardship. Volunteers will be provided with materials and assigned to specific areas of the city.', 'Parque Infante D. Pedro', '2024-04-05 07:00', '2024-04-05 18:00', 'public', 100, 6, 1, 6, 10),
('Healthcare Professional Training', 'Training course for healthcare volunteers, covering basic first aid, CPR, and emergency response. The program will address local health challenges, with a focus on chronic disease management, mental health support, and patient communication skills.', 'Hospital de Bragança', '2024-03-20 09:00', '2024-03-22 17:00', 'private', 40, 7, 4, 1, 11),
('Youth Leadership Summit', 'Leadership development for young community leaders. The summit will cover communication skills, project management, and civic engagement. Participants will have the opportunity to interact with successful leaders and develop plans for community-focused projects.', 'Palácio de Dom Manuel', '2024-05-10 08:30', '2024-05-12 16:30', 'public', 60, 8, 7, 7, 12),
('Digital Skills for Unemployed', 'Free training to enhance digital literacy for job seekers. Participants will learn computer basics, job application skills, and internet safety. The course aims to increase employability by equipping participants with essential digital skills for the modern workforce.', 'Parque das Nações', '2024-02-10 09:00', '2024-04-10 16:00', 'public', 35, 9, 11, 4, 13),
('Cultural Heritage Exhibition', 'Exhibit showcasing local cultural traditions, including crafts, art, and music. The event aims to promote cultural awareness and celebrate the diversity of the community. Visitors will have the opportunity to explore interactive displays and learn about the region’s history.', 'Castelo de Leiria', '2024-01-25 10:00', '2024-02-10 19:00', 'public', 75, 10, 10, 3, 14),
('Emergency Response Simulation', 'Disaster preparedness training that includes emergency simulations. Participants will learn about first aid, crisis management, and how to respond effectively in emergency situations. This workshop is aimed at volunteers and community leaders.', 'Escola Secundária Viriato', '2024-03-05 08:00', '2024-03-07 17:00', 'request_only', 30, 11, 19, 10, 15),
('Child Development Workshop', 'Training for early childhood educators focusing on developmental milestones, teaching strategies, and child psychology. The goal is to equip educators with the tools to support early childhood development in an effective and nurturing environment.', 'Universidade de Coimbra', '2024-04-15 09:00', '2024-04-16 17:00', 'private', 25, 12, 6, 2, 16),
('Social Justice Advocacy Training', 'Workshop focusing on rights, equality, and social justice issues. Participants will learn advocacy strategies, grassroots organizing, and legal frameworks to promote fairness and equity in their communities. The goal is to empower individuals to become active change agents.', 'Centro de Congressos de Viana do Castelo', '2024-02-20 09:30', '2024-02-22 16:30', 'public', 45, 13, 17, 8, 17),
('Wellness Marathon', 'Community run promoting health awareness. This marathon aims to raise awareness about the importance of physical activity and healthy living. The event will include various race categories and community activities to engage participants of all ages.', 'Ria de Aveiro', '2024-05-01 06:00', '2024-05-01 14:00', 'canceled', 200, 14, 1, 1, 18),
('Environmental Education Camp', 'Ecological awareness program for children and adults. Participants will engage in outdoor activities, sustainability workshops, and nature walks to learn about the environment and its protection. The camp aims to foster eco-consciousness and sustainable practices in the community.', 'Serra da Estrela', '2024-06-15 10:00', '2024-06-22 16:00', 'public', 50, 15, 9, 6, 19),
('Senior Companionship Program', 'Program designed to support elderly community members through regular visits and companionship. Volunteers will assist seniors with daily tasks, offer emotional support, and provide social interaction. The goal is to combat loneliness and improve the well-being of the elderly.', 'Centro Social de Castelo Branco', '2024-01-10 09:00', '2024-12-31 17:00', 'request_only', 40, 16, 5, 9, 20),
('Mental Health First Aid Training', 'Certification course that teaches how to provide initial mental health support. Participants will learn how to recognize the signs of mental illness, provide first aid, and connect individuals to appropriate mental health services. This course is aimed at community volunteers and caregivers.', 'Centro de Artes de Viseu', '2024-03-30 09:00', '2024-04-01 17:00', 'private', 30, 17, 19, 5, 21),
('Children''s Art Workshop', 'Creative program for kids to express themselves through art. The workshop will focus on developing creativity, self-expression, and motor skills through activities such as drawing, painting, and sculpture. It aims to foster confidence and emotional expression in children.', 'Museu de Leiria', '2024-05-15 10:00', '2024-05-22 15:00', 'public', 35, 18, 10, 3, 22),
('Tech Support for Non-Profits', 'Providing IT solutions and technical support to community organizations. This event will offer workshops on how to improve IT infrastructure, manage digital tools, and optimize operations for non-profit groups. Participants will receive hands-on training and resources.', 'Hub Criativo do Beato', '2024-02-05 08:00', '2024-04-05 16:00', 'public', 25, 19, 11, 4, 23),
('Community Food Bank Support', 'Organizing and distributing food resources to those in need. Volunteers will help collect food donations, sort, and distribute them to families in the community. This initiative is aimed at addressing food insecurity and promoting community solidarity.', 'Centro Comunitário de São Martinho', '2024-01-20 07:00', '2024-12-31 17:00', 'public', 60, 20, 12, 7, 24),
('Psychological Resilience Seminar', 'Workshop designed to teach mental strength, stress management, and coping strategies. Participants will learn techniques to handle stress, build resilience, and maintain mental health during challenging times. This seminar is ideal for individuals facing personal or professional challenges.', 'Teatro Viriato', '2024-04-10 10:00', '2024-04-11 15:00', 'request_only', 40, 1, 19, 5, 25),
('Cultural Music Festival', 'Festival celebrating diverse musical traditions from around the world. The event will feature performances from local and international musicians, showcasing various genres and cultural influences. It aims to promote cultural exchange and appreciation through music.', 'Castelo de Leiria', '2024-06-01 14:00', '2024-06-02 21:00', 'public', 100, 3, 10, 3, 27),
('Digital Literacy for Seniors', 'Introductory computer classes for seniors, focusing on basic digital skills like browsing the internet, email communication, and online security. The course aims to help older adults navigate the digital world and reduce feelings of isolation.', 'Biblioteca Municipal de Castelo Branco', '2024-03-15 09:00', '2024-05-15 15:00', 'public', 30, 4, 5, 4, 28),
('Environmental Policy Roundtable', 'Discussion on local sustainability initiatives and environmental policies. The roundtable will bring together community leaders, environmental advocates, and local officials to discuss the region’s environmental challenges and solutions. Participants will engage in collaborative problem-solving.', 'Parque Natural da Serra da Estrela', '2024-04-18 13:00', '2024-04-19 17:00', 'request_only', 35, 5, 9, 6, 29),
('Youth Sports Mentorship', 'Mentorship program for youth focused on athletic training and life skills. The program will combine physical training with leadership development, fostering personal growth and discipline. Coaches and mentors will provide guidance and support both on and off the field.', 'Complexo Desportivo de Évora', '2024-05-05 09:00', '2024-08-30 17:00', 'public', 45, 7, 7, 7, 31),
('Art Restoration Project', 'Project dedicated to preserving local historical artifacts through restoration efforts. Volunteers will assist in restoring and conserving artworks and objects of cultural significance. The project aims to protect the region’s heritage for future generations.', 'Museu de Leiria', '2024-03-10 10:00', '2024-06-10 17:00', 'private', 20, 8, 10, 3, 32),
('Homeless Shelter Support', 'Providing meals, support services, and outreach to homeless individuals. Volunteers will prepare and serve food, assist with daily needs, and provide emotional support. The initiative aims to improve the lives of individuals experiencing homelessness in the community.', 'Casa de Acolhimento de Funchal', '2024-01-01 08:00', '2024-12-31 20:00', 'public', 50, 9, 12, 7, 33),
('Emergency Medical Training', 'First aid and emergency medical training focused on crisis response. The course will teach basic medical interventions, CPR, and emergency management techniques. Participants will gain the skills necessary to assist during medical emergencies and disasters.', 'Hospital Distrital de Bragança', '2024-04-20 09:00', '2024-04-21 16:00', 'request_only', 35, 10, 4, 10, 34),
('Childhood Nutrition Program', 'Educational program focused on healthy eating habits for children. The course will teach parents and caregivers about childhood nutrition, offering tips on balanced meals and the importance of good dietary habits. The goal is to improve children’s health outcomes.', 'Escola Básica João de Deus', '2024-03-05 09:30', '2024-05-05 16:00', 'public', 40, 11, 14, 2, 35),
('Digital Rights Workshop', 'Workshop on online privacy, safety, and digital rights. Participants will learn about protecting personal information online, understanding privacy laws, and ensuring digital security. The goal is to empower individuals to navigate the digital world safely.', 'Parque das Nações', '2024-02-15 10:00', '2024-02-16 15:00', 'public', 30, 12, 11, 4, 36),
('Tech Support for Non-Profits', 'Providing IT support and technology solutions to local non-profits. The event will teach organizations how to optimize their digital tools and improve operations. Participants will receive practical guidance on using technology effectively within their non-profit missions.', 'Auditório Municipal de Viana do Castelo', '2024-06-10 09:00', '2024-06-12 17:00', 'public', 60, 13, 17, 8, 37),
('Wellness Yoga Retreat', 'A holistic yoga retreat focusing on stress management and overall wellness. Participants will engage in yoga, meditation, and relaxation techniques to reduce stress and promote physical and mental well-being. The retreat will offer a peaceful environment for personal rejuvenation.', 'Jardim Público de Évora', '2024-05-20 08:00', '2024-05-22 14:00', 'public', 25, 14, 7, 1, 38),
('Urban Garden Maintenance Project', 'Ongoing support for urban gardens through planting, care, and sustainable agricultural practices. Volunteers will maintain and enhance community gardens, ensuring their continued productivity and environmental impact. The project aims to create long-term sustainability.', 'Parque Natural da Serra da Estrela', '2024-04-25 08:00', '2024-07-26 18:00', 'public', 100, 15, 9, 6, 39),
('Career Counseling for Seniors', 'A workshop providing guidance on job search techniques, skill development, and resume building. Aimed at helping seniors transition into the workforce or pursue new career paths.', 'Centro Social de Castelo Branco', '2024-03-25 10:00', '2024-04-25 16:00', 'public', 35, 16, 5, 9, 40),
('Disaster Preparedness Workshop', 'A comprehensive session focused on community safety, emergency response strategies, and disaster planning. Participants will learn vital skills for handling natural or man-made crises effectively.', 'Centro de Formação de Viseu', '2024-02-05 10:00', '2024-02-06 15:00', 'public', 50, 17, 19, 10, 41);

-- Ongoing Events
INSERT INTO event (name, description, location, start_date, end_date, visibility, capacity, creator_id, district_id, category_id, file_id) VALUES
('Community Meal Preparation Program', 'Volunteers cook and distribute meals to vulnerable populations. This ongoing program aims to provide regular support to individuals in need, promoting food security and community care.', 'Centro Cultural de Aveiro', '2024-01-15 09:00', '2024-12-31 18:00', 'public', 75, 1, 1, 7, 42),
('Digital Mentorship for Seniors', 'Providing ongoing tech support and training to elderly community members. This program helps seniors become more confident with digital tools for communication and daily tasks.', 'Biblioteca Municipal de Castelo Branco', '2024-02-01 10:00', '2025-01-30 16:00', 'public', 40, 2, 5, 4, 43),
('Urban Garden Maintenance Project', 'Support community sustainable agriculture with planting, maintenance, and workshops. Volunteers help maintain urban gardens and promote eco-friendly practices.', 'Parque Urbano do Caia, Elvas', '2024-03-01 08:00', '2025-01-31 17:00', 'public', 50, 3, 13, 6, 44),
('Youth Tutoring and Homework Help', 'Providing academic support for underprivileged children. Volunteers help with homework, test preparation, and other educational needs to ensure academic success.', 'Biblioteca Almeida Garrett', '2024-01-20 14:00', '2024-12-26 17:30', 'public', 60, 4, 14, 2, 45),
('Community Health Screening Caravan', 'Offering free health check-ups and medical consultations on a monthly basis. This mobile program provides essential health services to underserved communities.', 'Pavilhão Naval de Setúbal', '2024-12-18 11:17', '2024-12-29 16:00', 'public', 100, 5, 16, 1, 46),
('Environmental Clean-Up Patrol', 'Ongoing efforts to clean local parks, streets, and community spaces. Volunteers engage in environmental conservation by removing waste and promoting cleaner neighborhoods.', 'Parque da Cidade de Aveiro', '2024-04-01 08:00', '2024-12-30 18:00', 'public', 80, 6, 1, 6, 47),
('Senior Companionship Program', 'Volunteers visit and support isolated elderly individuals. This program fosters social connections and provides companionship to seniors in the community.', 'Centro Social de Castelo Branco', '2024-01-10 10:00', '2025-02-28 15:00', 'request_only', 45, 7, 5, 9, 48),
('Art Therapy for Trauma Survivors', 'Creative workshops to help trauma survivors heal. This ongoing program uses art therapy as a tool for emotional and psychological recovery.', 'Galeria de Arte Contemporânea de Leiria', '2024-03-15 09:30', '2025-02-15 16:00', 'request_only', 30, 8, 10, 5, 49),
('Community Legal Aid Support', 'Providing volunteer assistance with legal consultations for low-income community members. This ongoing service helps individuals navigate legal challenges.', 'Junta de Freguesia de Beja', '2024-02-01 10:00', '2025-02-15 17:00', 'request_only', 35, 9, 2, 8, 50),
('Wildlife Conservation Monitoring', 'Monitoring wildlife habitats and conducting preservation activities. Volunteers help track animal populations and contribute to conservation efforts in natural parks.', 'Parque Natural da Serra da Estrela', '2024-05-01 08:30', '2025-01-30 15:30', 'public', 40, 10, 9, 6, 51),
('Mental Health Peer Support Network', 'An ongoing support group for individuals facing mental health challenges. Volunteers provide peer counseling and create a safe, supportive environment for healing.', 'Centro Cívico de Viseu', '2024-01-25 17:00', '2025-01-20 19:00', 'request_only', 25, 11, 19, 5, 52),
('Community Technology Lab', 'Providing digital skills training and computer access. This program helps bridge the digital divide by offering resources and support for technology learning.', 'Biblioteca Municipal de Lisboa', '2024-02-15 09:00', '2025-01-30 17:00', 'public', 50, 12, 11, 4, 53),
('Children''s Hospital Buddy Program', 'Volunteers provide companionship for pediatric patients. This ongoing program supports children in hospitals, offering emotional support and activities to improve their hospital experience.', 'Hospital Distrital de Bragança', '2024-03-01 10:00', '2025-01-15 16:00', 'request_only', 35, 13, 4, 2, 54),
('Sustainable Living Workshop Series', 'Monthly workshops focused on eco-friendly living practices. These sessions teach individuals how to reduce waste, conserve energy, and adopt sustainable habits.', 'Centro Ambiental de Beja', '2024-04-15 14:00', '2025-02-15 16:30', 'public', 40, 14, 2, 6, 55),
('Community Sports Mentorship', 'Providing athletic training and life skills coaching. This program aims to help young people build sports skills and develop important life lessons through mentorship.', 'Estádio Municipal de Évora', '2024-02-01 15:00', '2025-02-28 19:00', 'public', 55, 15, 7, 7, 56),
('Homeless Shelter Support Initiative', 'Volunteers assist in homeless shelters, providing meals and essential services. This ongoing program supports shelters in offering better care and services for those in need.', 'Centro Comunitário da Madeira', '2024-01-15 08:30', '2025-02-28 20:00', 'public', 70, 16, 12, 7, 57),
('Cultural Heritage Preservation', 'Documenting and protecting local cultural traditions. Volunteers help preserve and share community heritage through activities that safeguard cultural practices and artifacts.', 'Museu de Setúbal', '2024-05-01 10:00', '2025-02-28 17:00', 'request_only', 30, 17, 16, 3, 58),
('Community Garden Education Program', 'Workshops teaching gardening and nutrition. This ongoing program educates community members on sustainable gardening practices and the benefits of healthy eating.', 'Hortas Comunitárias de Portalegre', '2024-03-15 09:00', '2025-01-15 16:00', 'public', 45, 18, 13, 6, 59),
('Emergency Response Training Corps', 'Providing ongoing first responder training. Volunteers receive regular instruction on emergency response techniques and disaster preparedness skills.', 'Centro de Formação de Viseu', '2024-02-10 08:30', '2025-02-10 17:30', 'request_only', 40, 19, 19, 10, 60),
('Animal Shelter Support Program', 'Providing care and support for shelter animals. Volunteers help feed, clean, and care for animals in shelters to improve their well-being and chances for adoption.', 'Canil Municipal do Montijo', '2024-01-01 07:00', '2025-02-28 18:00', 'public', 60, 20, 16, 11, 61);


-- Future Events
INSERT INTO event (name, description, location, start_date, end_date, visibility, capacity, creator_id, district_id, category_id, file_id) VALUES
('Community Playground Renovation', 'Volunteers rebuild and repaint a local playground for children. This hands-on project helps improve community recreational spaces.', 'Parque da Cidade de Aveiro', '2024-07-15 08:00', '2024-07-20 17:00', 'public', 75, 1, 1, 7, 62),
('Beach Clean-Up Marathon', 'Organizing a large-scale cleanup of coastal areas to protect the environment. Volunteers collect waste and promote coastal conservation efforts.', 'Praia de Matosinhos', '2024-08-05 07:00', '2024-08-06 18:00', 'public', 200, 2, 14, 6, 63),
('Senior Home Painting Project', 'Volunteers refresh local senior living facilities with a new coat of paint. This initiative helps improve the living conditions of elderly residents.', 'Centro Social de Castelo Branco', '2024-09-10 09:00', '2024-09-15 17:30', 'public', 50, 3, 5, 9, 64),
('Food Bank Sorting and Packing', 'Organizing and distributing food to community members in need. Volunteers help with sorting and packaging food items for local food banks.', 'Norte Shopping', '2024-07-22 10:00', '2024-12-31 15:00', 'public', 100, 4, 14, 7, 65),
('Tree Planting Initiative', 'Volunteers participate in urban reforestation efforts by planting trees across city parks. This project promotes environmental sustainability.', 'Parque Natural da Serra da Estrela', '2024-08-15 08:00', '2024-09-30 16:30', 'public', 120, 5, 9, 6, 66),
('Computer Literacy for Seniors', 'Volunteers teach digital skills to elderly community members. The program empowers seniors with the technology skills needed for everyday life.', 'Biblioteca Municipal de Lisboa', '2024-10-01 09:00', '2024-11-30 15:30', 'public', 40, 6, 11, 4, 67),
('Community Garden Build', 'Volunteers help construct and prepare community vegetable gardens. This project promotes sustainable farming and healthy living.', 'Hortas Comunitárias de Portalegre', '2024-07-01 07:30', '2024-07-15 18:00', 'public', 60, 7, 13, 6, 68),
('Homeless Shelter Support Week', 'Volunteers provide comprehensive support to local homeless shelters. This week-long initiative offers meals, clothing, and essential services.', 'Centro Comunitário da Madeira', '2024-11-15 09:00', '2024-11-22 19:00', 'public', 80, 8, 12, 7, 69),
('Children''s Hospital Art Program', 'Volunteer artists create murals and engage with pediatric patients. The program brightens the hospital environment and offers creative therapy for children.', 'Hospital Distrital de Bragança', '2024-08-20 10:00', '2024-08-25 16:00', 'request_only', 30, 9, 4, 2, 70),
('Community Health Screening', 'Volunteers assist healthcare professionals in conducting free health check-ups. This program offers important health services to the community.', 'Base Naval de Setúbal', '2024-09-05 09:00', '2024-09-07 16:00', 'public', 100, 10, 16, 1, 71),
('Refugee Support Program', 'Providing language and integration support for new community members. Volunteers assist refugees in adapting to their new environment.', 'Junta de Freguesia de Beja', '2024-10-15 09:30', '2024-12-15 17:00', 'request_only', 50, 11, 2, 8, 72),
('Wildlife Habitat Restoration', 'Volunteers help restore local ecosystems by rebuilding and maintaining ecological zones. This program focuses on preserving wildlife habitats.', 'Parque Natural da Serra da Estrela', '2024-07-30 08:30', '2024-08-10 15:30', 'public', 75, 12, 9, 6, 73),
('Urban Mural Painting Project', 'Volunteers participate in beautifying city spaces by painting murals. This community art initiative promotes creativity and public engagement.', 'Confeitaria Henrique Carvalho', '2024-09-20 08:00', '2024-10-05 18:00', 'public', 40, 13, 14, 3, 74),
('Emergency Preparedness Training', 'Workshops teaching volunteer first responder and community safety skills. This program prepares participants for emergency situations and disaster response.', 'Centro de Formação de Viseu', '2024-11-01 09:00', '2024-11-03 16:30', 'request_only', 60, 14, 19, 10, 75),
('Mental Health Peer Support', 'Training and support for mental health volunteers. The program provides peer counseling and community-based support to individuals in need.', 'Centro Cívico de Viseu', '2024-08-10 09:30', '2024-08-17 16:30', 'request_only', 35, 15, 19, 5, 76),
('Book Sorting for Library', 'Volunteers help organize and prepare books for community libraries. This ongoing project supports local libraries by sorting and cataloging new materials.', 'Biblioteca Almeida Garrett', '2024-07-10 09:00', '2024-12-31 17:00', 'public', 50, 14, 3, 7, 77),
('Youth Sports Mentorship', 'Volunteer coaches help guide youth sports programs. The initiative focuses on athletic development and life skills for young people in the community.', 'Estádio do Dragão', '2024-09-01 14:00', '2024-11-30 18:00', 'public', 45, 17, 14, 7, 78),
('Community Kitchen Support', 'Volunteers prepare and serve meals to vulnerable populations. The initiative helps meet nutritional needs while fostering community support and solidarity.', 'Norte Shopping', '2024-08-01 10:00', '2024-12-31 16:00', 'public', 70, 18, 14, 7, 79),
('Tech Support for Seniors', 'Providing technology support to elderly community members. Volunteers assist seniors with digital tools to bridge the technology gap.', 'Escola Secundária João Gonçalves Zarco', '2024-10-01 08:30', '2024-11-15 16:30', 'public', 40, 19, 14, 4, 80),
('Environmental Education Camp', 'Running ecological awareness programs for youth. Volunteers help lead activities that teach environmental conservation and sustainability practices.', 'Centro Ambiental de Beja', '2024-07-15 09:00', '2024-07-26 17:30', 'public', 50, 20, 2, 6, 81),
('Community Art Restoration', 'Restoring local historical art pieces with volunteer help. This initiative ensures the preservation of community heritage through creative restoration efforts.', 'Museu de Setúbal', '2024-09-15 10:00', '2024-10-15 16:30', 'private', 25, 1, 16, 3, 82),
('Disaster Relief Supply Sorting', 'Volunteers organize and prepare emergency relief packages for disaster victims. The program ensures timely distribution of essential resources.', 'Centro de Formação de Viseu', '2024-11-10 09:00', '2024-11-17 16:00', 'public', 60, 2, 19, 10, 83),
('Children''s Literacy Program', 'Volunteers mentor children in reading at local schools. The program supports literacy development for young learners.', 'Biblioteca Almeida Garrett', '2024-08-25 10:00', '2024-12-15 15:30', 'public', 45, 3, 14, 2, 84),
('Community Clean-Up Campaign', 'A city-wide effort to clean and beautify public spaces. Volunteers participate in cleaning parks and streets.', 'Parque da Cidade de Aveiro', '2024-09-07 08:00', '2024-09-09 18:00', 'public', 200, 4, 1, 6, 85),
('Mental Health Awareness Walk', 'Volunteer support for a community walk focused on mental health awareness. The event promotes mental health education.', 'Parque da Cidade de Aveiro', '2024-10-10 09:30', '2024-10-10 15:30', 'public', 150, 5, 1, 5, 86),
('Sustainable Living Workshop', 'Volunteers teach eco-friendly lifestyle practices to promote sustainability. The workshop covers practical environmental tips.', 'Centro Ambiental de Beja', '2024-08-30 09:00', '2024-09-01 16:00', 'public', 40, 6, 2, 6, 87),
('Social Justice Advocacy Training', 'Volunteer training focused on supporting community rights and social justice initiatives. The program prepares advocates for action.', 'Junta de Freguesia de Beja', '2024-11-20 10:00', '2024-11-22 16:00', 'request_only', 35, 7, 2, 8, 88),
('Community Sports Day', 'An inclusive sports event for all ages. Volunteers help organize fun activities that encourage physical activity and community engagement.', 'Estádio do Dragão', '2024-07-20 09:00', '2024-07-21 17:00', 'public', 100, 8, 14, 7, 89),
('Digital Literacy for Immigrants', 'Volunteers teach technology skills to immigrants to help them integrate into the digital world. The program focuses on essential tech literacy.', 'Faculdade de Engenharia da Universidade do Porto', '2024-10-05 08:00', '2024-11-30 16:30', 'public', 50, 9, 14, 4, 90),
('Animal Shelter Support', 'Volunteers assist with animal care and maintenance at local shelters. The program supports the welfare of animals in need.', 'Canil Municipal do Montijo', '2024-08-15 07:30', '2024-12-31 18:00', 'public', 60, 10, 16, 11, 91),
('Community Health Education', 'Volunteers provide health education in local workshops. Topics include wellness, prevention, and community health resources.', 'Faculdade de Engenharia da Universidade do Porto', '2024-09-25 09:00', '2024-10-25 16:30', 'public', 45, 11, 14, 1, 92),
('Urban Gardening Workshop', 'Volunteers teach sustainable gardening techniques. The workshop helps community members create their own urban gardens.', 'Escola Básica Engenheiro Fernando Pinto de Oliveira', '2024-07-05 08:00', '2024-07-07 17:00', 'public', 40, 12, 14, 6, 93),
('Cultural Exchange Program', 'Facilitating understanding between international communities through cultural exchange programs. Volunteers support activities and learning opportunities.', 'Confeitaria Henrique Carvalho', '2024-10-20 10:00', '2024-11-20 16:00', 'public', 35, 13, 14, 3, 94),
('Youth Leadership Development', 'Mentorship program focused on developing leadership skills in youth. Volunteers provide guidance and inspiration for young community leaders.', 'Faculdade de Engenharia da Universidade do Porto', '2024-08-05 10:00', '2024-12-15 15:30', 'request_only', 50, 14, 14, 2, 95),
('Community Art Workshop', 'An inclusive art program where volunteers lead creative workshops for community members. The program focuses on artistic expression for all ages.', 'Escola Secundária João Gonçalves Zarco', '2024-09-10 09:00', '2024-09-15 17:00', 'public', 30, 15, 14, 3, 96),
('Senior Fitness Program', 'Volunteer fitness instructors lead fitness programs for seniors. The program promotes healthy aging and active lifestyles.', 'Escola Secundária João Gonçalves Zarco', '2024-10-01 08:30', '2024-11-30 16:30', 'public', 40, 16, 14, 9, 97),
('Emergency Medical Training', 'Volunteers participate in first aid and emergency response training. The program prepares participants for real-world emergency situations.', 'Estádio do Dragão', '2024-11-05 08:00', '2024-11-07 16:00', 'request_only', 50, 17, 14, 10, 98),
('Digital Accessibility Project', 'Volunteers work to make technology more accessible for disabled individuals. The project focuses on inclusive design and accessibility improvements.', 'Faculdade de Engenharia da Universidade do Porto', '2024-08-25 09:00', '2024-09-25 16:30', 'public', 35, 18, 14, 4, 99);

-- More Events
INSERT INTO event (name, description, location, start_date, end_date, visibility, capacity, creator_id, district_id, category_id, file_id) VALUES
('Community Health Check-up', 'A volunteer-driven health screening event to support underserved communities. Volunteers assist medical professionals in providing check-ups.', 'Quinta da Conceição', '2025-01-20 09:00', '2025-01-20 12:00', 'public', 50, 1, 14, 1, 100),
('Fitness for All', 'Volunteers help organize and lead fitness sessions for community members of all ages and abilities. The goal is to promote physical wellness.', 'Aeroporto Francisco Sá Carneiro', '2025-03-01 08:00', '2025-03-30 10:00', 'public', 30, 2, 14, 1, 101),
('Health Education Seminar', 'Provide guidance on healthy living, nutrition, and lifestyle choices to community members. The seminar focuses on long-term well-being.', 'Palácio de Cristal', '2025-04-05 10:00', '2025-04-05 13:00', 'public', 40, 3, 14, 1, 102),
('Blood Donation Drive', 'Assist in organizing a blood drive to encourage life-saving donations from community members. Volunteers manage logistics and donor care.', 'Parque do Carriçal', '2025-05-10 09:00', '2025-05-10 15:00', 'public', 50, 4, 14, 1, 103),
('Read to Succeed', 'Volunteers read books and conduct storytelling sessions for young children to promote literacy and a love of reading.', 'Exponor', '2025-02-01 10:00', '2025-02-28 12:00', 'public', 20, 5, 14, 2, 104),
('School Supplies Drive', 'Volunteers collect and distribute educational supplies to children in need. The drive aims to ensure every student has the tools for learning.', 'Sede U.DREAM', '2025-04-10 09:00', '2025-04-15 16:00', 'public', 40, 6, 14, 2, 105),
('Parenting Support Group', 'Provide parenting tips and guidance for new parents in underserved areas. Volunteers offer practical advice and emotional support.', 'Palácio de Cristal', '2025-03-01 11:00', '2025-03-01 13:00', 'public', 15, 6, 14, 2, 106),
('Tutoring for Kids', 'Support children with extra tutoring sessions to help improve their academic performance. Volunteers focus on math, reading, and other subjects.', 'Casa da Música', '2025-02-15 10:00', '2025-02-25 15:00', 'public', 25, 8, 14, 2, 107),
('Community Art Festival', 'Help organize and support an inclusive local art festival. Volunteers assist with event logistics, performances, and art installations.', 'Palácio de Cristal', '2025-01-25 09:00', '2025-01-28 18:00', 'public', 100, 9, 14, 3, 108),
('Museum Docent Training', 'Volunteer as a tour guide at a local museum. Training is provided to assist in leading educational tours for visitors.', 'Paragem de Metro da Trindade', '2025-02-15 14:00', '2025-02-20 17:00', 'public', 15, 7, 14, 3, 109),
('Youth Art Workshop', 'Guide children and teens in creating art at a workshop. Volunteers support creativity and artistic expression in young participants.', 'Exponor', '2025-03-10 09:00', '2025-03-12 16:00', 'public', 30, 11, 14, 3, 110),
('Cultural Exchange Program', 'Help organize and facilitate a cultural exchange event that highlights different cultures. Volunteers manage logistics and participant activities.', 'Aeroporto de Ponta Delgada', '2025-04-05 08:00', '2025-04-10 18:00', 'public', 50, 7, 20, 3, 111),
('Tech Basics Workshop', 'Teach basic computer skills to seniors and underserved individuals to improve their digital literacy and access to technology.', 'Sede U.DREAM', '2025-01-15 10:00', '2025-01-31 16:00', 'public', 25, 8, 14, 4, 112),
('Code for Kids', 'Mentor children in an introductory coding bootcamp. Volunteers help kids learn programming basics in a fun and engaging environment.', 'Faculdade de Engenharia da Universidade do Porto', '2025-03-05 10:00', '2025-03-10 15:00', 'public', 20, 9, 14, 4, 113),
('Digital Literacy Campaign', 'Assist in spreading awareness about online safety and digital skills in remote areas to promote safe internet usage.', 'Escola Secundária Francisco Franco', '2025-02-10 09:00', '2025-02-15 12:00', 'public', 50, 9, 12, 4, 114),
('Elderly Tech Education', 'Guide senior citizens in learning to use smartphones and computers to improve their digital skills and communication.', 'Sede U.DREAM', '2025-03-20 09:30', '2025-03-30 14:30', 'public', 20, 9, 14, 4, 115),
('Mental Health Awareness Talk', 'Help organize and run a seminar to raise awareness about mental health and well-being in the community.', 'Exponor', '2025-02-01 11:00', '2025-02-01 13:00', 'public', 80, 10, 14, 5, 116),
('Peer Support Training', 'Receive training to offer emotional support to peers. Volunteers help others in times of need with practical coping strategies.', 'Sede U.DREAM', '2025-04-01 09:00', '2025-04-07 17:00', 'public', 30, 10, 14, 5, 117),
('Self-Care Workshop', 'Educate the community on mental wellness practices, self-care strategies, and stress management techniques.', 'Quinta da Conceição', '2025-03-05 09:30', '2025-03-06 15:00', 'public', 40, 10, 14, 5, 118),
('Counseling Session for Kids', 'Provide psychological support and guidance to children in need of emotional help.', 'Palácio de Cristal', '2025-04-15 10:00', '2025-04-18 14:00', 'public', 20, 10, 14, 5, 119),
('Park Cleanup Drive', 'Join a community initiative to clean up and beautify the local park. Volunteers will help remove litter and maintain the park area.', 'Parque do Carriçal', '2025-01-10 09:00', '2025-01-10 12:00', 'public', 50, 11, 14, 6, 120),
('Tree Planting Day', 'Help plant trees to enhance the urban landscape and promote sustainability in the community.', 'Parque do Carriçal', '2025-03-20 09:00', '2025-03-20 14:00', 'public', 100, 11, 14, 6, 121),
('Recycling Awareness Workshop', 'Educate the community about recycling, waste management, and sustainable practices to help reduce environmental impact.', 'Sede U.DREAM', '2025-02-12 10:00', '2025-02-12 13:00', 'public', 30, 11, 14, 6, 122),
('Nature Conservation Day', 'Join a conservation event to help clean and protect nature areas. Volunteers participate in sustainability efforts to preserve the environment.', 'Junta de Freguesia de Câmara de Lobos', '2025-04-01 08:00', '2025-04-01 12:00', 'public', 80, 11, 12, 6, 123),
('Soup Kitchen Volunteering', 'Assist in preparing and serving meals to individuals in need at the local soup kitchen.', 'Sede U.DREAM', '2025-01-05 09:00', '2025-01-05 14:00', 'public', 25, 12, 14, 7, 124),
('Community Mentorship Program', 'Provide mentorship and guidance to underserved youth in the community to support their personal and academic development.', 'Exponor', '2025-04-01 08:00', '2025-06-30 17:00', 'public', 10, 12, 14, 7, 125),
('Clothing Drive', 'Organize a clothing collection event for low-income families. Volunteers assist with sorting, packaging, and distribution of clothing donations.', 'Sede U.DREAM', '2025-02-25 10:00', '2025-02-28 16:00', 'public', 50, 12, 14, 7, 126),
('Senior Assistance Program', 'Provide physical assistance and companionship to elderly individuals, offering help with daily activities and social engagement.', 'Casa da Música', '2025-03-10 10:00', '2025-03-20 17:00', 'public', 20, 12, 14, 7, 127),
('Equal Rights Advocacy Workshop', 'Join a volunteer group to promote equal rights and social justice through awareness-raising workshops and campaigns.', 'Palácio de Cristal', '2025-02-20 10:00', '2025-02-20 12:00', 'public', 75, 13, 14, 8, 128),
('Legal Aid Awareness Camp', 'Help organize a camp to provide free legal aid and raise awareness about legal rights and support for those in need.', 'Paragem de Metro da Trindade', '2025-03-15 09:00', '2025-03-16 12:00', 'public', 30, 13, 14, 8, 129),
('Voter Registration Campaign', 'Assist in educating citizens about the importance of voting and help register eligible voters in the community.', 'Sede U.DREAM', '2025-04-01 09:00', '2025-04-10 18:00', 'public', 100, 13, 14, 8, 130),
('Anti-Discrimination Rally', 'Help organize a rally to promote anti-discrimination and equal rights for all.', 'Exponor', '2025-05-01 10:00', '2025-05-03 16:00', 'public', 200, 13, 14, 8, 131),
('Senior Companionship Program', 'Volunteer to spend time with senior citizens, offering companionship and support.', 'Sede U.DREAM', '2025-01-12 10:00', '2025-03-12 16:00', 'public', 15, 14, 14, 9, 132),
('Health Aid for Seniors', 'Assist in organizing healthcare and wellness services for elderly individuals.', 'Casa da Música', '2025-02-15 08:00', '2025-02-28 12:00', 'public', 20, 14, 14, 9, 133),
('Memory Care Activities', 'Help organize activities for seniors with dementia to enhance their cognitive functions.', 'Sede U.DREAM', '2025-03-05 09:00', '2025-03-12 15:00', 'public', 10, 14, 14, 9, 134),
('Senior Transportation Assistance', 'Provide transportation to senior citizens for medical appointments and social activities.', 'Palácio de Cristal', '2025-04-15 10:00', '2025-04-20 14:00', 'public', 25, 14, 14, 9, 135),
('Disaster Preparedness Workshop', 'Host a workshop to educate the community about disaster preparedness and emergency response.', 'Escola Básica e Secundária Padre Manuel Álvares', '2025-11-23 10:00', '2025-11-23 13:00', 'public', 100, 15, 16, 10, 136),
('Flood Relief Collection', 'Help collect and distribute supplies for those affected by flooding.', 'Sede U.DREAM', '2025-04-05 09:00', '2025-04-15 15:00', 'public', 50, 15, 14, 10, 137),
('Emergency Response Training', 'Provide training for volunteers to assist in emergency response operations.', 'Exponor', '2025-03-01 09:00', '2025-03-05 15:00', 'public', 80, 15, 14, 10, 138),
('Disaster Relief Supply Distribution', 'Help distribute relief supplies to communities affected by natural disasters.', 'Parque do Carriçal', '2025-05-15 09:30', '2025-05-20 17:00', 'public', 60, 15, 14, 10, 139),
('Pet Adoption Drive', 'Assist in organizing an event to help pets find new homes.', 'Quinta da Conceição', '2025-02-25 09:00', '2025-02-26 13:00', 'public', 20, 16, 14, 11, 140),
('Wildlife Sanctuary Support', 'Assist in caring for and rehabilitating rescued wildlife at a local sanctuary.', 'Exponor', '2025-03-10 10:00', '2025-03-30 14:00', 'public', 15, 16, 14, 11, 141),
('Stray Animal Rescue', 'Help rescue and find homes for stray animals in need of care.', 'Sede U.DREAM', '2025-04-10 09:00', '2025-04-15 18:00', 'public', 30, 16, 14, 11, 142),
('Beach Cleanup for Marine Life', 'Volunteer for a beach cleanup to protect marine ecosystems and wildlife.', 'Praia da Calheta', '2025-05-01 08:00', '2025-05-01 13:00', 'public', 50, 16, 17, 11, 143);

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
-- Events 71-80
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
(4, 132), (14, 132), (24, 132), (34, 132), (44, 132), (51, 132);

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
(1, 3, 'This event was amazing!', FALSE, 45, FALSE, '2023-11-15 09:00:00'),
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

INSERT INTO file (comment_id, path) VALUES
(1, 'comment_pic_1.JPG'),
(1, 'comment_pic_2.JPG');

INSERT INTO comment (reply_to, comment_by_id, event_id, text, is_pinned, score, edited, date) VALUES
(3, 22, 1, 'Yes!', FALSE, 8, FALSE, '2023-11-15 10:32:00');

