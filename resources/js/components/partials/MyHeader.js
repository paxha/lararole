import React from 'react';
import {Layout} from "antd";

const {Header} = Layout;

function MyHeader() {
    return (
        <Header className="site-layout-background" style={{padding: 0}}/>
    );
}

export default MyHeader;
