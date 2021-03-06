<?php

namespace Catalog;

class Request
{
    protected $keys;
    protected $key;

    protected $proxies = array (
        '188.68.0.213:8085',
        '83.171.253.251:8085',
        '188.68.0.149:8085',
        '188.72.127.57:8085',
        '37.44.252.170:8085',
        '93.179.90.57:8085',
        '85.202.195.174:8085',
        '37.44.253.83:8085',
        '188.68.3.177:8085',
        '46.161.61.236:8085',
        '83.171.253.163:8085',
        '85.202.195.25:8085',
        '185.46.84.191:8085',
        '46.161.61.169:8085',
        '83.171.253.167:8085',
        '85.202.194.23:8085',
        '188.72.96.77:8085',
        '185.89.100.84:8085',
        '5.8.47.64:8085',
        '83.171.252.53:8085',
        '5.101.219.142:8085',
        '185.46.84.144:8085',
        '188.72.127.164:8085',
        '94.158.22.23:8085',
        '95.85.71.55:8085',
        '212.115.51.23:8085',
        '95.181.176.241:8085',
        '79.110.28.77:8085',
        '5.8.47.92:8085',
        '5.188.216.112:8085',
        '5.62.159.228:8085',
        '46.161.61.112:8085',
        '95.85.69.105:8085',
        '5.101.219.161:8085',
        '91.204.14.204:8085',

        '95.85.80.222:8085',
        '5.8.47.14:8085',
        '5.101.220.152:8085',
        '5.189.206.146:8085',
        '5.188.216.41:8085',
        '83.171.252.237:8085',
        '46.161.61.114:8085',
        '37.44.253.96:8085',
        '93.179.90.125:8085',
        '5.62.159.42:8085',
        '79.110.28.75:8085',
        '5.62.154.116:8085',
        '95.181.176.198:8085',
        '95.85.68.246:8085',
        '185.46.84.214:8085',
        '185.14.194.98:8085',
        '95.85.69.231:8085',
        '5.188.216.97:8085',
        '5.188.216.141:8085',
        '95.181.177.146:8085',
        '95.181.176.137:8085',
        '81.22.47.167:8085',
        '95.85.70.147:8085',
        '85.202.195.207:8085',
        '95.85.69.179:8085',
        '46.161.60.108:8085',
        '5.62.159.17:8085',
        '5.62.154.112:8085',
        '83.171.253.254:8085',
        '95.85.80.146:8085',
        '5.62.154.53:8085',
        '94.158.22.11:8085',
        '83.171.252.182:8085',
        '95.85.80.58:8085',
        '83.171.253.101:8085',
        '94.158.22.67:8085',
        '95.85.70.142:8085',
        '95.85.68.55:8085',
        '95.85.69.118:8085',
        '95.85.71.39:8085',
        '212.115.51.92:8085',
        '95.181.218.217:8085',
        '5.101.221.11:8085',
        '185.46.84.183:8085',
        '81.22.47.168:8085',
        '95.85.69.227:8085',
        '5.188.216.21:8085',
        '85.202.195.227:8085',
        '5.62.159.51:8085',
        '85.202.194.42:8085',
        '5.188.216.149:8085',
        '95.85.68.109:8085',
        '83.171.252.80:8085',
        '188.68.0.106:8085',
        '95.85.69.249:8085',
        '185.14.194.96:8085',
        '212.115.51.107:8085',
        '95.85.70.163:8085',
        '5.188.216.203:8085',
        '94.158.22.112:8085',
        '46.161.60.254:8085',
        '5.101.220.214:8085',
        '81.22.47.97:8085',
        '95.85.68.35:8085',
        '185.46.84.221:8085',
        '91.204.14.212:8085',
        '37.44.252.223:8085',
        '188.72.96.31:8085',
        '83.171.252.199:8085',
        '37.44.253.51:8085',
        '95.181.176.80:8085',
        '188.72.96.148:8085',
        '188.72.96.231:8085',
        '212.115.51.228:8085',
        '46.161.61.212:8085',
        '5.101.221.52:8085',
        '46.161.61.187:8085',
        '188.68.3.159:8085',
        '188.68.3.117:8085',
        '95.85.70.58:8085',
        '85.202.195.169:8085',
        '185.14.194.118:8085',
        '5.188.216.199:8085',
        '85.202.194.116:8085',
        '95.85.71.169:8085',
        '83.171.253.172:8085',
        '5.188.216.115:8085',
        '93.179.90.73:8085',
        '5.8.47.32:8085',
        '83.171.253.120:8085',
        '95.85.70.245:8085',
        '95.181.176.22:8085',
        '95.85.70.209:8085',
        '188.72.127.58:8085',
        '188.72.96.169:8085',
        '37.44.252.220:8085',
        '95.85.80.79:8085',
        '83.171.252.18:8085',
        '212.115.51.146:8085',
        '85.202.194.88:8085',
        '188.68.0.108:8085',
        '188.68.0.61:8085',
        '188.68.0.96:8085',
        '5.189.205.218:8085',
        '95.85.69.112:8085',
        '81.22.47.146:8085',
        '95.181.177.15:8085',
        '188.68.3.245:8085',
        '5.62.154.16:8085',
        '5.189.205.226:8085',
        '5.8.47.169:8085',
        '95.85.71.212:8085',
        '94.158.22.76:8085',
        '188.68.0.14:8085',
        '85.202.194.200:8085',
        '5.101.219.227:8085',
        '95.85.68.74:8085',
        '95.85.69.238:8085',
        '46.161.61.104:8085',
        '188.68.0.200:8085',
        '185.14.194.104:8085',
        '95.85.70.156:8085',
        '95.85.68.29:8085',
        '188.72.96.120:8085',
        '46.161.61.237:8085',
        '5.189.206.215:8085',
        '95.181.176.45:8085',
        '95.181.177.206:8085',
        '95.181.176.30:8085',
        '5.62.159.178:8085',
        '188.68.3.76:8085',
        '79.110.28.19:8085',
        '94.158.22.226:8085',
        '188.68.0.201:8085',
        '95.85.71.15:8085',
        '5.62.159.21:8085',
        '46.161.60.20:8085',
        '95.181.177.62:8085',
        '5.189.205.140:8085',
        '5.188.216.143:8085',
        '85.202.195.98:8085',
        '5.101.221.58:8085',
        '212.115.51.151:8085',
        '5.101.220.205:8085',
        '37.44.252.134:8085',
        '188.72.127.140:8085',
        '83.171.252.69:8085',
        '185.89.100.182:8085',
        '5.62.159.154:8085',
        '212.115.51.230:8085',
        '5.101.221.61:8085',
        '37.44.252.171:8085',
        '46.161.61.215:8085',
        '5.62.159.143:8085',
        '85.202.194.103:8085',
        '188.68.3.90:8085',
        '85.202.195.116:8085',
        '95.85.80.140:8085',
        '185.89.100.49:8085',
        '95.85.71.157:8085',
        '5.8.47.124:8085',
        '95.85.69.17:8085',
        '95.85.69.158:8085',
        '85.202.194.183:8085',
        '85.202.195.127:8085',
        '95.181.176.131:8085',
        '5.189.205.186:8085',
        '212.115.51.135:8085',
        '46.161.61.58:8085',
        '95.85.71.167:8085',
        '46.161.61.238:8085',
        '95.85.70.94:8085',
        '188.72.96.13:8085',
        '37.44.253.99:8085',
        '46.161.60.128:8085',
        '95.85.70.151:8085',
        '185.89.100.19:8085',
        '95.181.176.225:8085',
        '95.85.70.124:8085',
        '81.22.47.185:8085',
        '95.85.70.146:8085',
        '5.101.221.43:8085',
        '188.68.0.97:8085',
        '185.46.84.154:8085',
        '93.179.90.45:8085',
        '95.85.80.75:8085',
        '188.68.3.205:8085',
        '93.179.90.14:8085',
        '81.22.47.180:8085',
        '5.8.47.198:8085',
        '95.85.69.171:8085',
        '95.181.177.173:8085',
        '93.179.90.18:8085',
        '188.68.3.48:8085',
        '85.202.194.112:8085',
        '95.85.70.95:8085',
        '95.85.70.39:8085',
        '83.171.252.106:8085',
        '94.158.22.33:8085',
        '5.188.216.171:8085',
        '94.158.22.196:8085',
        '94.158.22.62:8085',
        '185.14.194.60:8085',
        '95.85.71.87:8085',
        '95.85.80.125:8085',
        '94.158.22.103:8085',
        '5.101.217.153:8085',
        '37.44.252.226:8085',
        '188.68.3.203:8085',
        '37.44.252.155:8085',
        '212.115.51.194:8085',
        '5.62.159.163:8085',
        '5.189.206.140:8085',
        '95.85.69.192:8085',
        '5.189.207.223:8085',
        '5.101.219.159:8085',
        '188.68.0.86:8085',
        '5.188.216.234:8085',
        '37.44.252.49:8085',
        '95.181.176.254:8085',
        '188.68.0.95:8085',
        '81.22.47.35:8085',
        '83.171.252.233:8085',
        '81.22.47.108:8085',
        '188.68.0.242:8085',
        '5.101.220.163:8085',
        '5.62.154.101:8085',
        '95.85.70.149:8085',
        '5.101.219.200:8085',
        '5.189.206.234:8085',
        '5.101.221.77:8085',
        '5.188.216.73:8085',
        '188.72.96.242:8085',
        '5.101.220.196:8085',
        '83.171.252.73:8085',
        '37.44.252.241:8085',
        '95.85.69.213:8085',
        '46.161.60.110:8085',
        '83.171.252.131:8085',
        '5.62.159.82:8085',
        '46.161.61.175:8085',
        '185.46.84.228:8085',
        '95.85.80.87:8085',
        '95.181.177.213:8085',
        '95.85.71.234:8085',
        '46.161.60.101:8085',
        '37.44.253.161:8085',
        '83.171.253.214:8085',
        '95.181.177.44:8085',
        '46.161.60.190:8085',
        '95.85.69.167:8085',
        '93.179.90.77:8085',
        '83.171.253.18:8085',
        '188.68.3.19:8085',
        '188.72.96.153:8085',
        '5.101.220.188:8085',
        '85.202.194.201:8085',
        '5.8.47.111:8085',
        '37.44.252.154:8085',
        '5.101.219.232:8085',
        '95.181.177.142:8085',
        '5.8.47.42:8085',
        '95.85.80.163:8085',
        '5.8.47.132:8085',
        '188.68.0.199:8085',
        '5.189.207.154:8085',
        '46.161.61.189:8085',
        '95.85.80.177:8085',
        '188.68.3.125:8085',
        '5.188.216.111:8085',
        '95.85.69.251:8085',
        '95.85.69.47:8085',
        '83.171.253.63:8085',
        '37.44.253.250:8085',
        '95.85.69.123:8085',
        '37.44.252.227:8085',
        '95.85.80.200:8085',
        '85.202.194.98:8085',
        '83.171.252.145:8085',
        '81.22.47.115:8085',
        '37.44.252.41:8085',
        '5.8.47.86:8085',
        '94.158.22.233:8085',
        '83.171.252.240:8085',
        '46.161.61.92:8085',
        '95.85.68.102:8085',
        '5.8.47.71:8085',
        '46.161.60.237:8085',
        '5.8.47.62:8085',
        '95.181.177.18:8085',
        '188.72.96.167:8085',
        '5.189.205.238:8085',
        '95.85.80.65:8085',
        '83.171.252.79:8085',
        '95.85.68.54:8085',
        '46.161.60.27:8085',
        '95.181.176.79:8085',
        '95.85.70.191:8085',
        '212.115.51.109:8085',
        '95.85.70.162:8085',
        '94.158.22.51:8085',
        '95.85.80.19:8085',
        '94.158.22.17:8085',
        '85.202.195.59:8085',
        '212.115.51.159:8085',
        '5.62.159.116:8085',
        '95.85.70.83:8085',
        '5.101.217.231:8085',
        '95.85.80.235:8085',
        '37.44.252.123:8085',
        '37.44.252.142:8085',
        '46.161.61.228:8085',
        '79.110.28.27:8085',
        '95.85.70.121:8085',
        '91.204.14.176:8085',
        '83.171.252.72:8085',
        '5.188.216.91:8085',
        '5.101.221.27:8085',
        '5.189.205.169:8085',
        '188.72.96.69:8085',
        '188.68.3.176:8085',
        '95.85.70.16:8085',
        '188.72.127.72:8085',
        '94.158.22.219:8085',
        '185.89.100.38:8085',
        '95.181.176.21:8085',
        '5.62.159.158:8085',
        '81.22.47.56:8085',
        '85.202.194.252:8085',
        '37.44.253.212:8085',
        '37.44.252.69:8085',
        '93.179.90.121:8085',
        '185.89.100.100:8085',
        '185.14.194.20:8085',
        '94.158.22.130:8085',
        '95.85.69.48:8085',
        '5.101.217.140:8085',
        '85.202.194.32:8085',
        '188.68.3.200:8085',
        '185.46.84.238:8085',
        '81.22.47.86:8085',
        '5.62.159.218:8085',
        '5.188.216.177:8085',
        '93.179.90.12:8085',
        '37.44.253.72:8085',
        '5.62.154.54:8085',
        '95.85.71.63:8085',
        '5.62.159.128:8085',
        '83.171.252.241:8085',
        '5.188.216.232:8085',
        '83.171.253.19:8085',
        '212.115.51.241:8085',
        '83.171.252.188:8085',
        '95.181.176.179:8085',
        '188.72.96.179:8085',
        '5.101.219.246:8085',
        '81.22.47.94:8085',
        '46.161.60.59:8085',
        '185.89.100.158:8085',
        '81.22.47.100:8085',
        '185.14.194.49:8085',
        '95.85.70.30:8085',
        '95.181.177.77:8085',
        '85.202.194.77:8085',
        '95.181.177.53:8085',
        '95.85.71.147:8085',
        '5.189.207.168:8085',
        '185.14.194.52:8085',
        '95.85.68.174:8085',
        '188.68.3.163:8085',
        '95.181.218.205:8085',
        '81.22.47.19:8085',
        '37.44.253.126:8085',
        '5.62.154.42:8085',
        '95.181.177.47:8085',
        '95.85.71.105:8085',
        '85.202.195.141:8085',
        '5.8.47.81:8085',
        '81.22.47.198:8085',
        '95.85.80.246:8085',
        '83.171.252.248:8085',
        '83.171.253.97:8085',
        '5.189.206.171:8085',
        '83.171.252.124:8085',
        '188.72.96.133:8085',
        '85.202.194.96:8085',
        '95.181.176.185:8085',
        '188.68.0.53:8085',
        '5.62.159.167:8085',
        '46.161.61.15:8085',
        '83.171.253.111:8085',
        '188.68.3.148:8085',
        '94.158.22.79:8085',
        '83.171.253.27:8085',
        '188.68.0.21:8085',
        '188.68.3.44:8085',
        '85.202.194.214:8085',
        '95.85.70.188:8085',
        '95.181.218.180:8085',
        '188.72.96.96:8085',
        '85.202.194.152:8085',
        '185.89.100.135:8085',
        '37.44.252.15:8085',
        '5.101.220.209:8085',
        '188.68.3.35:8085',
        '46.161.61.35:8085',
        '37.44.252.61:8085',
        '37.44.253.90:8085',
        '95.85.70.168:8085',
        '188.68.3.232:8085',
        '83.171.252.103:8085',
        '5.101.217.162:8085',
        '85.202.194.72:8085',
        '94.158.22.111:8085',
        '91.204.14.208:8085',
        '85.202.195.121:8085',
        '46.161.61.181:8085',
        '83.171.252.136:8085',
        '46.161.60.249:8085',
        '5.101.217.234:8085',
        '95.181.177.65:8085',
        '188.72.96.117:8085',
        '188.72.96.16:8085',
        '95.85.70.145:8085',
        '46.161.60.118:8085',
        '95.85.70.174:8085',
        '5.62.154.26:8085',
        '94.158.22.89:8085',
        '188.72.127.221:8085',
        '188.68.0.66:8085',
        '5.101.219.229:8085',
        '185.89.100.194:8085',
        '5.189.205.176:8085',
        '91.204.14.199:8085',
        '85.202.195.102:8085',
        '95.85.71.211:8085',
        '46.161.61.184:8085',
        '185.46.84.174:8085',
        '95.85.69.223:8085',
        '5.189.207.142:8085',
        '81.22.47.145:8085',
        '95.85.68.86:8085',
        '81.22.47.62:8085',
        '5.189.206.154:8085',
        '46.161.60.67:8085',
        '212.115.51.224:8085',
        '83.171.253.17:8085',
        '85.202.195.195:8085',
        '188.68.0.204:8085',
        '212.115.51.119:8085',
    );

    public function __construct(\Buzz\Browser $httpClient, array $keys)
    {
        $this->httpClient = $httpClient;
        $this->keys = $keys;
    }

    public function prepare()
    {
        $this->key = $this->getRandomKey();
        $this->httpClient->getClient()->setProxy($this->getRandomProxy());
    }

    protected function getRandomKey()
    {
        return $this->keys[array_rand($this->keys)];
    }

    protected function getRandomProxy()
    {
        return $this->proxies[array_rand($this->proxies)];
    }
}