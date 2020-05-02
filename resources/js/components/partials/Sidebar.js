import React, {useState} from 'react';
import {Link} from "react-router-dom";
import {Layout, Menu} from "antd";
import {DeleteOutlined, DeploymentUnitOutlined, HomeOutlined, TeamOutlined} from '@ant-design/icons';

const {Sider} = Layout;

function Sidebar() {
    console.log(window.location.pathname.split('/'));
    const [collapsed, setCollapsed] = useState(false);

    function onCollapse() {
        setCollapsed(!collapsed);
    }

    return (
        <Sider theme="light" collapsible collapsed={collapsed} onCollapse={onCollapse}>
            <div className="logo"/>
            <Menu defaultSelectedKeys={[window.location.pathname.split('/')[2] || 'home']} mode="inline">
                <Menu.Item key="home">
                    <Link to='/lararole' replace>
                        <HomeOutlined/>
                        <span>Home</span>
                    </Link>
                </Menu.Item>
                <Menu.Item key="module">
                    <Link to='/lararole/module' replace>
                        <DeploymentUnitOutlined/>
                        <span>Module</span>
                    </Link>
                </Menu.Item>
            </Menu>
        </Sider>
    );
}

export default Sidebar;
