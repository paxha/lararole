import React from 'react';
import ReactDOM from 'react-dom';
import {Layout} from 'antd';
import MyHeader from "./partials/MyHeader";
import MyFooter from "./partials/MyFooter";
import {BrowserRouter as Router, Route} from "react-router-dom";
import ModuleIndex from "./module";
import Sidebar from "./partials/Sidebar";
import ModuleCreate from "./module/Create";
import Home from "./Home";
import ModuleEdit from "./module/Edit";

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
                        <Route exact path="/lararole/module/:id?/create" component={ModuleCreate}/>
                        <Route exact
                               path="/lararole/module/:id/edit"
                               render={props => <ModuleEdit {...props} />}
                        />
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
