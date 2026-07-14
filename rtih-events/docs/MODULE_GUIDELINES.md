# Module Guidelines

To build the RTIH Experience Platform, we are utilizing a Modular Architecture.

## What is a Module?
A Module is a self-contained slice of business functionality (e.g., `Events`, `Applications`, `Users`). It contains everything it needs to function: Routes, Controllers, Models, Services, and Events.

## Directory Structure
```text
app/Modules/{ModuleName}/
├── Controllers/
├── Models/
├── Services/
├── Events/
├── Listeners/
├── Policies/
└── Jobs/
```

## Rules for Modules
1. **Isolation**: Modules should not directly query the database tables of other modules. If the `Events` module needs data about `Users`, it should do so via relationships defined on the Model, or by calling a Service/Contract provided by the `Users` module.
2. **Core Dependency**: All modules can freely depend on the `Core` module (`app/Modules/Core`). The `Core` module must NEVER depend on specific feature modules.
3. **Event-Driven**: When something significant happens (e.g., an Application is Approved), dispatch a Domain Event (`ApplicationApproved`). Do not hardcode the email sending logic directly in the approval service. Other modules (or internal listeners) can subscribe to this event.
4. **Extension Points**: Build interfaces (`Contracts`) for features that are planned but not yet built (e.g. `NotificationServiceContract`), rather than building empty classes.
