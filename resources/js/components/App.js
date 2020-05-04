import React from 'react';
import ReactDOM from 'react-dom';
import {Layout} from 'antd';
import MyHeader from "./partials/MyHeader";
import MyFooter from "./partials/MyFooter";
import {BrowserRouter as Router, Route} from "react-router-dom";
import ModuleIndex from "./module";
import Sidebar from "./partials/Sidebar";
import Home from "./Home";

const {Content} = Layout;

function App() {
    return (
        <Layout style={{minHeight: '100vh'}}>
            <Router>
                <Sidebar/>
                <Layout className="site-layout">
                    <MyHeader/>
                    <Content style={{margin: '0 16px'}}>
                        <Route exact path="/lararole" component={Home}/>
                        <Route exact path="/lararole/module" component={ModuleIndex}/>
                    </Content>
                    <MyFooter/>
                </Layout>
            </Router>
        </Layout>
    );
}

export default App;

if (document.getElementById('app')) {
    ReactDOM.render(<App/>, document.getElementById('app'));
}
