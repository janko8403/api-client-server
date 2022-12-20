ALTER TABLE assemblyOrders ADD customerId INT DEFAULT NULL;
ALTER TABLE assemblyOrders ADD CONSTRAINT FK_4E793139F17FD7A5 FOREIGN KEY (customerId) REFERENCES customer (id);
CREATE INDEX IDX_4E793139F17FD7A5 ON assemblyOrders (customerId);

UPDATE assemblyOrders SET customerId = 2;
