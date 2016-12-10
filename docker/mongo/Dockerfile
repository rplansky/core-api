FROM mongo:3.0
RUN chown -R mongodb:mongodb /data/db
RUN chmod 0777 -R /data/db

CMD ["mongod", "--smallfiles"]