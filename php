apiVersion: extensions/v1beta1
kind: Deployment
metadata:
  name: php-dbconnect
  labels:
    "oracletraining": lamp
spec:
  replicas: 3
  revisionHistoryLimit: 5
  selector:
    matchLabels:
      app: php-dbconnect
  template:
    metadata:
      labels:
        app: php-dbconnect
    spec:
      containers:
      - image: sathishbob/example-php-dbconnect
        imagePullPolicy: Always
        name: php-dbconnect
        resources:
          requests:
            cpu: 100m
            memory: 1Gi
        env:
          - name: MYSQL_USER
            valueFrom:
              secretKeyRef:
                name: mysql-credentials
                key: user
          - name: MYSQL_PASSWORD
            valueFrom:
              secretKeyRef:
                name: mysql-credentials
                key: Password
          - name: MYSQL_HOST
            value: mysql.default.svc.cluster.local
        livenessProbe:
          tcpSocket:
            port: 80
        ports:
        - containerPort: 80

---

apiVersion: v1
kind: Service
metadata:
  name: web
  labels:
    "oracletraining": lamp
spec:
  ports:
  - port: 80
    protocol: TCP
  selector:
    app: php-dbconnect
  type: LoadBalancer
