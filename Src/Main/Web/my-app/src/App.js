import React from 'react';
import './App.css';
import 'antd/dist/antd.css';
import './main.css';
import 'moment/locale/zh-cn';
import {Button, message, Icon, Layout, Menu, Breadcrumb, Input, Checkbox} from "antd";
import TextArea from "antd/es/input/TextArea";

const {Header, Content, Footer, Sider} = Layout;
const CheckboxGroup = Checkbox.Group;
const plainOptions = ['image', 'video', 'audio'];

let counter = 0;

class App extends React.Component {

    constructor(props) {
        super(props);
        this.socket = null;
        this.textRef = null;
        this.state = {
            date: String,
            ws: WebSocket,
            textareaMessage: [],
            url: String,
            depth: Number,
            filter: Number,
            interval: Number,
            frequency: String,
            downloadType: [],
        };
    }

    componentDidMount(): void {
        this.connectWS();
    }

    handleChange = date => {
        message.info(`你选择的时间是：${date ? date.format('YYYY-MM-DD') : '未选择'}`);
        this.setState({date});
    };

    connectWS = () => {
        //this.ws = new WebSocket("ws://119.28.23.252:8690/ws");
        this.ws = new WebSocket("ws://47.100.103.97:8690/ws");
        this.ws.onmessage = this.recvMessage;
        this.ws.onclose = this.socketOnClose;
        this.showMessage("socket连接成功\r\n");
    };

    /**
     * 发送消息
     * */
    sendMessage = (info) => {
        if(info != null)
            return this.ws.send(JSON.stringify({cmd:999}));
        let sendData = JSON.stringify({
            "cmd": 10001,
            "group": "c_b_ex",
            "url": this.state.url,
            "depth": this.state.depth,
            "filter": this.state.filter,
            "frequency": this.state.frequency,
            "interval": this.state.interval,
            "type": this.state.downloadType,
        });
        if(this.ws.readyState)
          return this.ws.send(sendData);
    };

    /**
     * 发送消息
     * */
    recvMessage = (info) => {
        let cmd:Number = JSON.parse(info.data).cmd;
        if(cmd != null && cmd === 999)
            this.sendMessage(cmd);
        this.showMessage("服务器消息：" + ((JSON.parse(info.data).cmd != null && JSON.parse(info.data).status != null) ? JSON.parse(info.data).msg : JSON.parse(info.data).cmd) + "\r\n");
        document.getElementsByTagName('textarea')[0].scrollTop = document.getElementsByTagName('textarea')[0].scrollHeight;
    };

    /**
     * 断开连接
     * */
    socketOnClose = (info) => {
        this.showMessage("与服务器断开连接\r\n");
    };

    /**
     * 展示消息
     * */
    showMessage = (info) => {
        let messages = this.state.textareaMessage;
        if (messages.length > 30)
            messages.shift();
        messages.push("line：" + (counter++) + " " + info);
        this.setState({
            textareaMessage: messages
        });
    };

    /**
     * 断开连接
     * */
    socketClose = (info) => {
        if (this.ws.readyState)
            this.ws.close();
        this.showMessage("与服务器断开连接\r\n");
    };

    /**
     * 重连
     * */
    socketReconnect = (info) => {
        this.socketClose();
        this.connectWS();
        if (this.ws.readyState)
            this.showMessage("重连服务器\r\n");
    };

    render() {
        return <Layout>
            <Header className="header">
                <div className="logo"/>
                <Menu
                    theme="dark"
                    mode="horizontal"
                    defaultSelectedKeys={['2']}
                    style={{lineHeight: '64px'}}
                >
                    <Menu.Item key="1">首页</Menu.Item>
                    <Menu.Item key="2">采集器</Menu.Item>
                    <Menu.Item key="3">关于</Menu.Item>
                </Menu>
            </Header>
            <Content style={{padding: '0 50px'}}>
                <Breadcrumb style={{margin: '16px 0'}}>
                    <Breadcrumb.Item>采集器</Breadcrumb.Item>
                </Breadcrumb>
                <Layout style={{padding: '24px 0', background: '#fff'}}>
                    <Sider width={240} style={{background: '#fff'}}>
                        <Input
                            suffix={<Icon type={"link"} style={{color: 'rgba(0,0,0,.25)'}}/>}
                            placeholder={"采集地址"}
                            style={{margin: '0 0 10px 5px'}}
                            onChange={event => {
                                this.setState({url: event.currentTarget.value})
                            }}
                        />
                        <Input
                            suffix={<Icon type={'down-square'} style={{color: 'rgba(0,0,0,.25)'}}/>}
                            placeholder={'采集深度'}
                            type={"number"}
                            style={{margin: '0 0 10px 5px'}}
                            onChange={event => {
                                this.setState({depth: event.currentTarget.value})
                            }}
                            max={100}
                        />
                        <Input
                            suffix={<Icon type="clock-circle" style={{color: 'rgba(0,0,0,.25)'}}/>}
                            placeholder={'采集间隔(ms)'}
                            type={"number"}
                            style={{margin: '0 0 10px 5px'}}
                            onChange={event => {
                                this.setState({interval: event.currentTarget.value})
                            }}
                            max={10000}
                        />
                        <Input
                            suffix={<Icon type="file" style={{color: 'rgba(0,0,0,.25)'}}/>}
                            placeholder={'大小过滤(Kb)'}
                            type={"number"}
                            style={{margin: '0 0 10px 5px'}}
                            onChange={event => {
                                this.setState({filter: event.currentTarget.value})
                            }}
                            max={10240}
                        />
                        <Input
                            suffix={<Icon type="calendar" style={{color: 'rgba(0,0,0,.25)'}}/>}
                            placeholder={'定时采集(年/月/日/时/分/秒)'}
                            style={{margin: '0 0 10px 5px'}}
                            defaultValue={'0/0/0/0/0/0'}
                            onChange={event => {
                                this.setState({frequency: event.currentTarget.value})
                            }}
                        />
                        <CheckboxGroup
                            options={plainOptions}
                            style={{margin: '0 0 10px 5px'}}
                            onChange={event => {
                                this.setState({downloadType: event})
                            }}
                        />
                        <Button type={'default'} block style={{margin: '0 0 10px 5px'}}
                                onClick={() => this.sendMessage()}>发送</Button>
                        <Button type={'default'} block style={{margin: '0 0 10px 5px'}}
                                onClick={() => this.socketReconnect()}>重连</Button>
                        <Button type={'default'} block style={{margin: '0 0 10px 5px'}}
                                onClick={() => this.socketClose()}>断开连接</Button>
                    </Sider>
                    <Content style={{padding: '0 24px', minHeight: 280, width: "100%"}} color={"red"}>
                        <TextArea
                            value={this.state.textareaMessage.join('')}
                            style={{color: "#55FF55", backgroundColor: "#000", height:"100%",width:"100%"}}
                            disabled={true}
                            className={"textareaBar"}
                            ref={(element) => {this.textRef = element}}
                        >
                        </TextArea>
                    </Content>
                </Layout>
            </Content>
            <Footer style={{textAlign: 'center'}}>Ant Design ©2020 Created by CCL</Footer>
        </Layout>
    }
}

export default App;
